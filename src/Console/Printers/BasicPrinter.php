<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\RollbackScope;
use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\FilesEditor;
use Cerbero\ConsoleTasker\Tasks\Task;

/**
 * The basic printer.
 *
 */
class BasicPrinter extends AbstractPrinter
{
    /**
     * Print the given task while it's running
     *
     * @param Task $task
     * @return void
     */
    public function printRunningTask(Task $task): void
    {
        $purpose = ucfirst($task->getPurpose());

        $this->output->write("{$purpose}: " . static::LOADING_MARK);
    }

    /**
     * Print the given succeeded task
     *
     * @param Task $task
     * @return string
     */
    protected function printSucceededTask(Task $task): void
    {
        $purpose = ucfirst($task->getPurpose());

        $this->output->write("\033[2K\r"); // it deletes the line
        $this->output->writeln($purpose . ': <info>' . static::SUCCESS_MARK . '</info>');
    }

    /**
     * Print the given skipped task
     *
     * @param Task $task
     * @return string
     */
    protected function printSkippedTask(Task $task): void
    {
        $reason = $task->getSkippingReason();
        $output = vsprintf('%s: <fg=yellow;bg=default>%s %s</>', [
            ucfirst($task->getPurpose()),
            static::SKIPPED_MARK,
            $reason ? "skipped because $reason" : 'skipped',
        ]);

        $this->output->write("\033[2K\r"); // it deletes the line
        $this->output->writeln($output);
    }

    /**
     * Print the given failed task
     *
     * @param Task $task
     * @return string
     */
    protected function printFailedTask(Task $task): void
    {
        $output = vsprintf('%s: <fg=red;bg=default>%s %s</>', [
            ucfirst($task->getPurpose()),
            static::FAILURE_MARK,
            $task->getError(),
        ]);

        $this->output->write("\033[2K\r"); // it deletes the line
        $this->output->writeln($output);
    }

    /**
     * Print console output when all tasks succeeded
     *
     * @param Summary $summary
     * @return void
     */
    protected function printSuccess(Summary $summary): void
    {
        $created = $updated = $toUpdate = [];

        foreach ($summary->getSucceededTasks() as $task) {
            if (!$task instanceof FilesEditor) {
                continue;
            }

            /** @var FilesEditor $task */
            foreach ($task->getFiles() as $file) {
                $array = $file->wasCreated() ? 'created' : 'updated';
                $$array[] = $file->getRelativePath();

                if ($file->needsManualUpdate()) {
                    $toUpdate[] = ucfirst($file->getManualUpdateReason()) . ' in ' . $file->getRelativePath();
                }
            }
        }

        sort($created);
        sort($updated);

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
     * Print out the succeeded rollbacks
     *
     * @param \Cerbero\ConsoleTasker\RollbackScope[] $rollbacks
     * @return void
     */
    protected function printSucceededRollbacks(array $rollbacks): void
    {
        $rollbackResults = array_map(function (RollbackScope $scope) {
            return vsprintf('The task to %s was rolled back due to failure to %s', [
                lcfirst($scope->rolledbackTask->getPurpose()),
                lcfirst($scope->failedTask->getPurpose()),
            ]);
        }, $rollbacks);

        $this->listItems('Rolled back tasks', $rollbackResults);
    }

    /**
     * Print out the failed rollbacks
     *
     * @param \Cerbero\ConsoleTasker\RollbackScope[] $rollbacks
     * @return void
     */
    protected function printFailedRollbacks(array $rollbacks): void
    {
        $rollbackResults = array_map(function (RollbackScope $scope) {
            $purpose = lcfirst($scope->rolledbackTask->getPurpose());
            $exception = $scope->rolledbackTask->getRollbackException();
            $reason = $exception ? ': ' . $exception->getMessage() : null;

            return "The task to {$purpose} was not rolled back" . $reason;
        }, $rollbacks);

        $this->listItems('Failed rollbacks', $rollbackResults);
    }
}
