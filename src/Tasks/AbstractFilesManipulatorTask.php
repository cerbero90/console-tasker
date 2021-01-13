<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\ManipulatedFile;

/**
 * The abstract files manipulator task.
 *
 */
abstract class AbstractFilesManipulatorTask extends AbstractTask
{
    /**
     * The files being manipulated.
     *
     * @var ManipulatedFile[]
     */
    protected $manipulatedFiles = [];

    /**
     * Retrieve the files being manipulated
     *
     * @return ManipulatedFile[]
     */
    public function getManipulatedFiles(): array
    {
        return $this->manipulatedFiles;
    }

    /**
     * Retrieve an instance of manipulated file with the given path
     *
     * @param string $path
     * @return ManipulatedFile
     */
    protected function file(string $path): ManipulatedFile
    {
        return $this->manipulatedFiles[] = new ManipulatedFile($path);
    }

    /**
     * Revert this task
     *
     * @return void
     */
    protected function revert(): void
    {
        foreach ($this->getManipulatedFiles() as $file) {
            if ($file->wasCreated()) {
                unlink($file->getPath());
            } else {
                file_put_contents($file->getPath(), $file->getOriginalContent());
            }
        }
    }
}
