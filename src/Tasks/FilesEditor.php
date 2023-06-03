<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Concerns\AccessesFiles;
use Cerbero\ConsoleTasker\File;

/**
 * The abstract files manipulator task.
 *
 */
abstract class FilesEditor extends Task
{
    use AccessesFiles;

    /**
     * The stub to print when displaying the summary.
     *
     * @var string|null
     */
    protected static ?string $summaryStub = 'console-tasker::files-summary';

    /**
     * Rollback this task
     *
     * @return mixed
     */
    protected function rollback()
    {
        $succeeded = true;

        foreach ($this->getFiles() as $file) {
            if (false !== $index = array_search($file, static::$summaryData['files'], true)) {
                unset(static::$summaryData['files'][$index]);
            }

            if ($file->wasCreated()) {
                $succeeded = $succeeded && $this->rollbackCreatedFile($file);
            } else {
                $succeeded = $succeeded && $this->rollbackUpdatedFile($file);
            }
        }

        return $succeeded;
    }

    /**
     * Rollback the given created file
     *
     * @param File $file
     * @return bool
     */
    protected function rollbackCreatedFile(File $file): bool
    {
        if (unlink($file->getPath())) {
            return true;
        }

        $this->rollbackFailureReason ??= 'unable to delete ' . basename($file->getPath());

        return false;
    }

    /**
     * Rollback the given updated file
     *
     * @param File $file
     * @return bool
     */
    protected function rollbackUpdatedFile(File $file): bool
    {
        if (file_put_contents($file->getPath(), $file->getOriginalContent()) !== false) {
            return true;
        }

        $this->rollbackFailureReason ??= 'unable to revert the changes to ' . basename($file->getPath());

        return false;
    }
}
