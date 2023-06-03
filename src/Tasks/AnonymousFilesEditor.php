<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Definitions\FilesEditorDefinition;

/**
 * The anonymous files editor.
 *
 */
class AnonymousFilesEditor extends FilesEditor
{
    /**
     * Instantiate the class.
     *
     * @param FilesEditorDefinition $definition
     */
    public function __construct(protected FilesEditorDefinition $definition)
    {
    }

    /**
     * Run the task
     *
     * @return mixed
     */
    protected function run()
    {
        //
    }
}
