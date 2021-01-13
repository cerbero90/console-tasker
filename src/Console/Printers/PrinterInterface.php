<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\Tasks\AbstractTask;

/**
 * The printer interface.
 *
 */
interface PrinterInterface
{
    /**
     * Print the execution of the given task
     *
     * @param AbstractTask $task
     * @param callable $callback
     * @return void
     */
    public function printRunningTask(AbstractTask $task, callable $callback): void;

    /**
     * Print the tasks summary
     *
     * @return void
     */
    public function printSummary(): void;
}
