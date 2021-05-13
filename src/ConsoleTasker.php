<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Console\Printers\PrinterInterface;
use Cerbero\ConsoleTasker\Exceptions\StoppingTaskException;
use Cerbero\ConsoleTasker\Tasks\AbstractTask;
use Cerbero\ConsoleTasker\Tasks\InvalidTask;
use Cerbero\ConsoleTasker\Traits\IOAware;
use Illuminate\Container\Container;
use Throwable;

/**
 * The console tasker.
 *
 */
class ConsoleTasker
{
    use IOAware;

    /**
     * The console printer.
     *
     * @var PrinterInterface
     */
    protected $printer;

    /**
     * Instantiate the class.
     *
     * @param PrinterInterface $printer
     */
    public function __construct(PrinterInterface $printer)
    {
        $this->printer = $printer;
    }

    /**
     * Run the given tasks
     *
     * @param iterable $tasks
     * @return void
     * @throws Throwable
     */
    public function runTasks(iterable $tasks): void
    {
        $this->newLine();

        try {
            foreach ($tasks as $class) {
                $this->processTask($this->resolveTask($class));
            }
        } catch (Throwable $e) {
            // catch exception to throw later
        }

        $this->printer->printSummary();

        if ($exception = Summary::instance()->getFirstException() ?: $e ?? null) {
            throw $exception;
        }
    }

    /**
     * Retrieve the resolved instance of the given task class
     *
     * @param string $class
     * @return AbstractTask
     */
    protected function resolveTask(string $class): AbstractTask
    {
        $invalidTask = new InvalidTask($class);

        try {
            $task = Container::getInstance()->make($class);
            $valid = $task instanceof AbstractTask;
        } catch (Throwable $e) {
            $invalidTask->setException($e);
            $valid = false;
        }

        if ($valid) {
            return $task->setIO($this->input, $this->output);
        }

        Summary::instance()->addInvalidTask($invalidTask);

        return $invalidTask;
    }

    /**
     * Process the given task
     *
     * @param AbstractTask $task
     * @return bool
     * @throws Throwable
     */
    protected function processTask(AbstractTask $task): bool
    {
        $this->printer->printRunningTask($task, function (AbstractTask $task) {
            $this->runTask($task);
        });

        if (!$task->shouldRun()) {
            return false;
        }

        Summary::instance()->addExecutedTask($task);

        if (!$task->succeeded()) {
            $this->handleFailedTask($task);
        }

        return $task->succeeded();
    }

    /**
     * Run the given task
     *
     * @param AbstractTask $task
     * @return void
     */
    protected function runTask(AbstractTask $task): void
    {
        if (!$task->shouldRun()) {
            return;
        }

        try {
            $task->setResult($task->run() !== false);
        } catch (Throwable $e) {
            $task->setException($e);
            $task->setError($e->getMessage());
            $task->setResult(false);
        }
    }

    /**
     * Handle the given failed task
     *
     * @param AbstractTask $task
     * @return void
     * @throws Throwable
     */
    protected function handleFailedTask(AbstractTask $task): void
    {
        $succeededTasks = Summary::instance()->getSucceededTasks();

        $this->rollbackTasksDueTo($succeededTasks, $task);

        if ($task->stopsSuccessiveTasksOnFailure()) {
            throw $task->getException() ?: new StoppingTaskException($task);
        }
    }

    /**
     * Rollback the given tasks due to the provided failed task
     *
     * @param AbstractTask[] $tasks
     * @param AbstractTask $failedTask
     * @return void
     */
    protected function rollbackTasksDueTo(array $tasks, AbstractTask $failedTask): void
    {
        if (empty($tasks)) {
            return;
        }

        $task = array_pop($tasks);

        if (!$task->ranRollback() && $task->shouldRollbackDueTo($failedTask)) {
            $this->rollbackTask($task);
            Summary::instance()->addRolledbackTask($task, $failedTask);

            if ($task->rolledback()) {
                $this->rollbackTasksDueTo($tasks, $task);
            }
        }

        $this->rollbackTasksDueTo($tasks, $failedTask);
    }

    /**
     * Rollback the given task
     *
     * @param AbstractTask $task
     * @return void
     */
    protected function rollbackTask(AbstractTask $task): void
    {
        try {
            $task->rollback();
        } catch (Throwable $e) {
            $task->setRollbackException($e);
        }
    }
}
