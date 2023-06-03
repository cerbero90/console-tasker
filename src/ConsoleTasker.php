<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Console\Printers\Printer;
use Cerbero\ConsoleTasker\Exceptions\StoppingTaskException;
use Cerbero\ConsoleTasker\Tasks\Task;
use Throwable;

/**
 * The console tasker.
 *
 */
class ConsoleTasker
{
    /**
     * Instantiate the class.
     *
     * @param Printer $printer
     */
    public function __construct(protected Printer $printer)
    {
    }

    /**
     * Run the given tasks
     *
     * @param Task[] $tasks
     * @return bool
     */
    public function runTasks(iterable $tasks): bool
    {
        $summary = Summary::instance();

        try {
            foreach ($tasks as $task) {
                $this->processTask($task);
            }
        } catch (Throwable $e) {
            $summary->addException($e);
        }

        $this->printer->printSummary($summary);

        return $summary->succeeded();
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
        if ($failedTask->rollbacksOnFailure()) {
            $this->rollbackTasksDueTo($failedTask, Summary::instance()->getSucceededTasks());
        }

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
        TasksCollector::instance()->clear();

        Summary::instance()->clear();
    }
}
