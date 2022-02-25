<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\Task;

/**
 * The printer interface.
 *
 */
interface Printer
{
    /**
     * Print the given task while it's being executed
     *
     * @param Task $task
     * @return void
     */
    public function printExecutingTask(Task $task): void;

    /**
     * Print the given task after it was executed
     *
     * @param Task $task
     * @return void
     */
    public function printExecutedTask(Task $task): void;

    /**
     * Print the given task after it rolled back
     *
     * @param Task $task
     * @return void
     */
    public function printRolledbackTask(Task $task): void;

    /**
     * Print the tasks summary
     *
     * @param Summary $summary
     * @return void
     */
    public function printSummary(Summary $summary): void;
}
