<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Cerbero\CommandValidator\ValidatesInput;
use Cerbero\ConsoleTasker\ConsoleTasker;
use Cerbero\ConsoleTasker\Data;

/**
 * The trait to run tasks.
 *
 */
trait RunsTasks
{
    use ValidatesInput;

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
     * @return bool
     */
    protected function runTasks(string ...$tasks): bool
    {
        return $this->laravel->make(ConsoleTasker::class)
            ->setIO($this->input, $this->output)
            ->setData($this->mergeData())
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
     * Retrieve the merged data
     *
     * @return Data
     */
    protected function mergeData(): Data
    {
        $data = new Data();

        foreach ($this->getNativeDefinition()->getArguments() as $key => $input) {
            $data[$key] = $this->argument($key);
        }

        foreach ($this->getNativeDefinition()->getOptions() as $key => $input) {
            $data[$key] = $this->option($key);
        }

        return $data->merge($this->data());
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

    /**
     * Retrieve the rules to validate data against
     *
     * @return array
     */
    protected function rules(): array
    {
        return $this->rules ?? [];
    }
}
