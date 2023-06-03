<?php

namespace Cerbero\ConsoleTasker\Definitions;

use Cerbero\ConsoleTasker\Tasks\AnonymousFileCreator;
use Cerbero\ConsoleTasker\Tasks\AnonymousFilesEditor;
use Cerbero\ConsoleTasker\Tasks\AnonymousTask;
use Cerbero\ConsoleTasker\TasksCollector;
use Closure;

/**
 * The task definer.
 *
 */
class TaskDefiner
{
    /**
     * Instantiate the class.
     *
     * @param string $purpose
     */
    public function __construct(protected string $purpose)
    {
    }

    /**
     * Define the logic to run
     *
     * @param Closure $callback
     * @return TaskDefinition
     */
    public function run(Closure $callback): TaskDefinition
    {
        $definition = TaskDefinition::to($this->purpose)->run($callback);

        TasksCollector::instance()->collect(new AnonymousTask($definition));

        return $definition;
    }

    /**
     * Define the file to create
     *
     * @param Closure|string $file
     * @return TaskDefinition
     */
    public function create(Closure|string $file): FileCreatorDefinition
    {
        $definition = FileCreatorDefinition::to($this->purpose)->target($file);

        TasksCollector::instance()->collect(new AnonymousFileCreator($definition));

        return $definition;
    }

    /**
     * Define the file to update
     *
     * @param Closure|string $file
     * @return TaskDefinition
     */
    public function edit(Closure|string $file): FilesEditorDefinition
    {
        $definition = FilesEditorDefinition::to($this->purpose)->target($file);

        TasksCollector::instance()->collect(new AnonymousFilesEditor($definition));

        return $definition;
    }
}
