<?php

namespace Cerbero\ConsoleTasker\Definitions;

use Closure;

/**
 * The task definition.
 *
 */
class TaskDefinition extends Definition
{
    /**
     * The logic to run.
     *
     * @var Closure
     */
    protected Closure $run;

    /**
     * The logic to run on rollback.
     *
     * @var Closure
     */
    protected Closure $onRollback;

    /**
     * Set the logic to run
     *
     * @param Closure $callback
     * @return static
     */
    public function run(Closure $callback): static
    {
        $this->run = $callback;

        return $this;
    }

    /**
     * Define the logic to run on rollback
     *
     * @param Closure $callback
     * @return static
     */
    public function onRollback(Closure $callback): static
    {
        $this->onRollback = $callback;

        return $this;
    }
}
