<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\Task;

/**
 * The console printer.
 *
 */
abstract class Printer
{
    /**
     * The printed summary stubs.
     *
     * @var array
     */
    protected array $printedSummaryStubs = [];

    /**
     * Print the given task while it's being executed
     *
     * @param Task $task
     * @return void
     */
    abstract public function printExecutingTask(Task $task): void;

    /**
     * Print the given task after it was executed
     *
     * @param Task $task
     * @return void
     */
    abstract public function printExecutedTask(Task $task): void;

    /**
     * Print the given task after it rolled back
     *
     * @param Task $task
     * @return void
     */
    abstract public function printRolledbackTask(Task $task): void;

    /**
     * Print the given summary stub
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    abstract protected function printSummaryStub(string $path, array $data): void;

    /**
     * Print the tasks error
     *
     * @param Summary $summary
     * @return void
     */
    abstract protected function printErrors(Summary $summary): void;

    /**
     * Print the tasks summary
     *
     * @param Summary $summary
     * @return void
     */
    public function printSummary(Summary $summary): void
    {
        if ($summary->succeeded()) {
            $this->printResults($summary);
        } else {
            $this->printErrors($summary);
        }
    }

    /**
     * Print the tasks result
     *
     * @param Summary $summary
     * @return void
     */
    protected function printResults(Summary $summary): void
    {
        foreach ($summary->getSucceededTasks() as $task) {
            if ($stub = $task::getSummaryStub()) {
                $this->printedSummaryStubs[$stub] ??= $this->printSummaryStub($stub, $task::getSummaryData());
            }
        }
    }
}
