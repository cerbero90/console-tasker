<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\File;

/**
 * The abstract files manipulator task.
 *
 */
abstract class FilesEditor extends Task
{
    /**
     * The files being manipulated.
     *
     * @var File[]
     */
    protected $files = [];

    /**
     * Retrieve the files being manipulated
     *
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Retrieve an instance of the file with the given path
     *
     * @param string $path
     * @return File
     */
    protected function file(string $path): File
    {
        return $this->files[] = new File($path);
    }

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
