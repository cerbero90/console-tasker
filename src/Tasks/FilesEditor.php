<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Concerns\AccessesFiles;

/**
 * The abstract files manipulator task.
 *
 */
abstract class FilesEditor extends Task
{
    use AccessesFiles;

    /**
     * Rollback this task
     *
     * @return void
     */
    protected function rollback(): void
    {
        foreach ($this->getFiles() as $file) {
            if ($file->wasCreated()) {
                unlink($file->getPath());
            } else {
                file_put_contents($file->getPath(), $file->getOriginalContent());
            }
        }
    }
}
