<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Definitions\TaskDefinition;
use Closure;

/**
 * The anonymous task.
 *
 */
class AnonymousTask extends Task
{
    /**
     * Instantiate the class.
     *
     * @param TaskDefinition $definition
     */
    public function __construct(protected TaskDefinition $definition)
    {
    }

    /**
     * Run the task
     *
     * @return mixed
     */
    protected function run()
    {
        $callback = Closure::bind($this->definition->run, $this);

        return $this->app->call($callback);
    }
}
