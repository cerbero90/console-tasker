<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Cerbero\ConsoleTasker\ConsoleTasker;
use Cerbero\ConsoleTasker\Data;

/**
 * The trait to run tasks.
 *
 */
trait RunsTasks
{
    /**
     * Handle the console command
     *
     * @return int
     */
    public function handle(): int
    {
        return $this->runTasks(...$this->tasks()) ? 0 : 1;
    }

    /**
     * Run the given tasks
     *
     * @param string ...$tasks
     * @return int
     */
    protected function runTasks(string ...$tasks): int
    {
        return $this->laravel->make(ConsoleTasker::class)
            ->setIO($this->input, $this->output)
            ->setData(new Data($this->data()))
            ->runTasks(...$tasks);
    }

    /**
     * Retrieve the list of tasks to run
     *
     * @return array
     */
    protected function tasks(): array
    {
        return $this->tasks ?? [];
    }

    /**
     * Retrieve the data for tasks and stubs
     *
     * @return array
     */
    protected function data(): array
    {
        return [];
    }
}
