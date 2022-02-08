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
     * Print the given task while it's running
     *
     * @param Task $task
     * @return void
     */
    public function printRunningTask(Task $task): void;

    /**
     * Print the given task after it ran
     *
     * @param Task $task
     * @return void
     */
    public function printRunTask(Task $task): void;

    /**
     * Print the tasks summary
     *
     * @param Summary $summary
     * @return void
     */
    public function printSummary(Summary $summary): void;
}
