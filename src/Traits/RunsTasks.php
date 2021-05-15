<?php

namespace Cerbero\ConsoleTasker\Traits;

use Cerbero\ConsoleTasker\ConsoleTasker;
use Illuminate\Container\Container;

/**
 * The trait to run tasks.
 *
 */
trait RunsTasks
{
    /**
     * Run the given tasks
     *
     * @param iterable $tasks
     * @return void
     */
    protected function runTasks(iterable $tasks): void
    {
        Container::getInstance()->make(ConsoleTasker::class)->setIO($this->input, $this->output)->runTasks($tasks);
    }
}
