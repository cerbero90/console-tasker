<?php

namespace Cerbero\ConsoleTasker\Traits;

use Cerbero\ConsoleTasker\ConsoleTasker;

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
        $this->laravel->make(ConsoleTasker::class)->setIO($this->input, $this->output)->runTasks($tasks);
    }
}
