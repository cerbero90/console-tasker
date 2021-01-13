<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\RollbackScope;
use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\AbstractFilesManipulatorTask;
use Cerbero\ConsoleTasker\Tasks\AbstractTask;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;

/**
 * The default printer.
 *
 */
class DefaultPrinter implements PrinterInterface
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
     * The console output.
     *
     * @var OutputStyle
     */
    protected $output;

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
     * Print the execution of the given task
     *
     * @param AbstractTask $task
     * @param callable $callback
     * @return void
     */
    public function printRunningTask(AbstractTask $task, callable $callback): void
    {
        $purpose = ucfirst($task->getPurpose());

        $this->output->write("{$purpose}: " . static::LOADING_MARK);

        $callback($task);

        if ($task->shouldRun()) {
            $result = $task->succeeded() ? $this->formatTaskSuccess($task) : $this->formatTaskFailure($task);
        } else {
            $result = $this->formatTaskSkipping($task);
        }

        if ($this->output->isDecorated()) {
            $this->output->write("\033[2K\r"); // delete line
        } else {
            $this->newLine();
        }

        $this->output->writeln("{$purpose}: {$result}");
    }

    /**
     * Format the success message of the given task
     *
     * @param AbstractTask $task
     * @return string
     */
    protected function formatTaskSuccess(AbstractTask $task): string
    {
        return '<info>' . static::SUCCESS_MARK . '</info>';
    }

    /**
     * Format the failure message of the given task
     *
     * @param AbstractTask $task
     * @return string
     */
    protected function formatTaskFailure(AbstractTask $task): string
    {
        return sprintf('<fg=red;bg=default>%s %s</>', static::FAILURE_MARK, $task->getError());
    }

    /**
     * Format the skipping message of the given task
     *
     * @param AbstractTask $task
     * @return string
     */
    protected function formatTaskSkipping(AbstractTask $task): string
    {
        $reason = $task->getSkippingReason();
        $text = $reason ? "skipped because $reason" : 'skipped';

        return sprintf('<fg=yellow;bg=default>%s %s</>', static::SKIPPED_MARK, $text);
    }

    /**
     * Print the tasks summary
     *
     * @return void
     */
    public function printSummary(): void
    {
        if (Summary::instance()->succeeded()) {
            $this->printSuccess();
        } else {
            $this->printFailure();
        }
    }

    /**
     * Print console output when all tasks succeeded
     *
     * @return void
     */
    protected function printSuccess(): void
    {
        $created = $updated = $toUpdate = [];

        foreach (Summary::instance()->getSucceededTasks() as $task) {
            if (!$task instanceof AbstractFilesManipulatorTask) {
                continue;
            }

            foreach ($task->getManipulatedFiles() as $file) {
                $array = $file->wasCreated() ? 'created' : 'updated';
                $$array[] = $file->getRelativePath();

                if ($file->needsManualUpdate()) {
                    $toUpdate[] = ucfirst($file->getManualUpdateReason()) . ' in ' . $file->getRelativePath();
                }
            }
        }

        $this->listItems('Created files', $created);
        $this->listItems('Updated files', $updated);
        $this->listItems('Files to update manually', $toUpdate);
    }

    /**
     * List the given items with the provided title
     *
     * @param array $items
     * @param string $title
     * @return void
     */
    protected function listItems(string $title, array $items): void
    {
        if ($items) {
            $this->output->title($title);
            $this->output->listing($items);
        }
    }

    /**
     * Print console output when not all tasks succeeded
     *
     * @return void
     */
    protected function printFailure(): void
    {
        $rollbacks = $failedRollbacks = [];

        foreach (Summary::instance()->getRolledbackTasks() as $scope) {
            if ($scope->rolledbackTask->rolledback()) {
                $rollbacks[] = $this->formatRollback($scope);
            } else {
                $failedRollbacks[] = $this->formatFailedRollback($scope);
            }
        }

        $this->listItems('Rolled back tasks', $rollbacks);
        $this->listItems('Failed rollbacks', $failedRollbacks);
    }

    /**
     * Retrieve the message to show when a task rolled back
     *
     * @param RollbackScope $scope
     * @return string
     */
    protected function formatRollback(RollbackScope $scope): string
    {
        return vsprintf('The task to %s was rolled back due to failure to %s', [
            lcfirst($scope->rolledbackTask->getPurpose()),
            lcfirst($scope->failedTask->getPurpose()),
        ]);
    }

    /**
     * Retrieve the message to show when a task failed to rollback
     *
     * @param RollbackScope $scope
     * @return string
     */
    protected function formatFailedRollback(RollbackScope $scope): string
    {
        $purpose = lcfirst($scope->rolledbackTask->getPurpose());
        $exception = $scope->rolledbackTask->getRollbackException();
        $reason = $exception ? ': ' . $exception->getMessage() : null;

        return "The task to {$purpose} was not rolled back" . $reason;
    }
}
