<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Concerns\DataAware;
use Cerbero\ConsoleTasker\Concerns\IOAware;
use Cerbero\ConsoleTasker\Console\Printers\Printer;
use Cerbero\ConsoleTasker\Exceptions\StoppingTaskException;
use Cerbero\ConsoleTasker\Tasks\InvalidTask;
use Cerbero\ConsoleTasker\Tasks\Task;
use Illuminate\Contracts\Foundation\Application;
use Throwable;

/**
 * The console tasker.
 *
 */
class ConsoleTasker
{
    use DataAware;
    use IOAware;

    /**
     * Instantiate the class.
     *
     * @param Application $app
     * @param Printer $printer
     */
    public function __construct(protected Application $app, protected Printer $printer)
    {
    }

    /**
     * Run the given tasks
     *
     * @param string ...$tasks
     * @return bool
     */
    public function runTasks(string ...$tasks): bool
    {
        $summary = Summary::instance();

        try {
            foreach ($tasks as $task) {
                $this->processTask($this->resolveTask($task));
            }
        } catch (Throwable $e) {
            $summary->addException($e);
        }

        $this->printer->printSummary($summary);

        return $summary->succeeded();
    }

    /**
     * Retrieve the resolved instance of the given task class
     *
     * @param string $class
     * @return Task
     */
    protected function resolveTask(string $class): Task
    {
        try {
            $task = $this->app->make($class);
        } catch (Throwable $e) {
            $task = new InvalidTask($class, $e);
        }

        if (!$task instanceof Task) {
            $task = new InvalidTask($class);
        }

        return $task->setIO($this->input, $this->output)->setApp($this->app)->setData($this->getData());
    }

    /**
     * Process the given task
     *
     * @param Task $task
     * @return bool
     */
    protected function processTask(Task $task): bool
    {
        $this->printer->printExecutingTask($task);

        $succeeded = $task->perform();

        Summary::instance()->addExecutedTask($task);

        $this->printer->printExecutedTask($task);

        if ($task->failed()) {
            $this->handleFailedTask($task);
        }

        return $succeeded;
    }

    /**
     * Handle the given failed task
     *
     * @param Task $failedTask
     * @return void
     * @throws Throwable
     */
    protected function handleFailedTask(Task $failedTask): void
    {
        $succeededTasks = Summary::instance()->getSucceededTasks();

        $this->rollbackTasksDueTo($failedTask, $succeededTasks);

        if ($failedTask->stopsOnFailure()) {
            throw $failedTask->getException() ?: new StoppingTaskException($failedTask);
        }
    }

    /**
     * Rollback the given tasks due to the provided failed task
     *
     * @param Task $failedTask
     * @param Task[] $tasks
     * @return void
     */
    protected function rollbackTasksDueTo(Task $failedTask, array $tasks): void
    {
        if (empty($tasks)) {
            return;
        }

        $task = array_pop($tasks);

        if (!$task->ranRollback() && $task->shouldRollbackDueTo($failedTask)) {
            $task->revert();

            Summary::instance()->addRolledbackTask($task, $failedTask);

            $this->printer->printRolledbackTask($task);

            if ($task->rolledback()) {
                $this->rollbackTasksDueTo($task, $tasks);
            }
        }

        $this->rollbackTasksDueTo($failedTask, $tasks);
    }

    /**
     * Handle the instance destruction
     *
     * @return void
     */
    public function __destruct()
    {
        Summary::instance()->clear();
    }
}
