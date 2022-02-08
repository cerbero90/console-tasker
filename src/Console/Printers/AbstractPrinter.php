<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\Task;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;

/**
 * The abstract printer.
 *
 */
abstract class AbstractPrinter implements Printer
{
    use InteractsWithIO;

    /**
     * The loading mark.
     *
     * @var string
     */
    protected const LOADING_MARK = '⏳';

    /**
     * The success mark.
     *
     * @var string
     */
    protected const SUCCESS_MARK = '✔';

    /**
     * The failure mark.
     *
     * @var string
     */
    protected const FAILURE_MARK = '✖';

    /**
     * The failure mark.
     *
     * @var string
     */
    protected const SKIPPED_MARK = '⏩';

    /**
     * Instantiate the class.
     *
     * @param OutputStyle $output
     */
    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    /**
     * Print the given task while it's running
     *
     * @param Task $task
     * @return void
     */
    abstract public function printRunningTask(Task $task): void;

    /**
     * Print the given succeeded task
     *
     * @param Task $task
     * @return string
     */
    abstract protected function printSucceededTask(Task $task): void;

    /**
     * Print the given skipped task
     *
     * @param Task $task
     * @return string
     */
    abstract protected function printSkippedTask(Task $task): void;

    /**
     * Print the given failed task
     *
     * @param Task $task
     * @return string
     */
    abstract protected function printFailedTask(Task $task): void;

    /**
     * Print output when tasks succeeded or were skipped
     *
     * @param Summary $summary
     * @return void
     */
    abstract protected function printSuccess(Summary $summary): void;

    /**
     * Print out the succeeded rollbacks
     *
     * @param \Cerbero\ConsoleTasker\RollbackScope[] $rollbacks
     * @return void
     */
    abstract protected function printSucceededRollbacks(array $rollbacks): void;

    /**
     * Print out the failed rollbacks
     *
     * @param \Cerbero\ConsoleTasker\RollbackScope[] $rollbacks
     * @return void
     */
    abstract protected function printFailedRollbacks(array $rollbacks): void;

    /**
     * Print the given task after it ran
     *
     * @param Task $task
     * @return void
     */
    public function printRunTask(Task $task): void
    {
        match (true) {
            $task->succeeded() => $this->printSucceededTask($task),
            $task->wasSkipped() => $this->printSkippedTask($task),
            !$task->succeeded() => $this->printFailedTask($task),
        };
    }

    /**
     * Print the tasks summary
     *
     * @param Summary $summary
     * @return void
     */
    public function printSummary(Summary $summary): void
    {
        if ($summary->succeeded()) {
            $this->printSuccess($summary);
        } else {
            $this->printFailure($summary);
        }
    }

    /**
     * Print output when tasks failed
     *
     * @param Summary $summary
     * @return void
     */
    protected function printFailure(Summary $summary): void
    {
        if ($succeededRollbacks = $summary->getSucceededRollbacks()) {
            $this->printSucceededRollbacks($succeededRollbacks);
        }

        if ($failedRollbacks = $summary->getFailedRollbacks()) {
            $this->printFailedRollbacks($failedRollbacks);
        }
    }
}
