<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Cerbero\CommandValidator\ValidatesInput;
use Cerbero\ConsoleTasker\ConsoleTasker;
use Cerbero\ConsoleTasker\Data;
use Cerbero\ConsoleTasker\TasksResolver;

/**
 * The trait to run tasks.
 *
 */
trait RunsTasks
{
    use ConfigAware;
    use ValidatesInput;

    /**
     * Handle the console command
     *
     * @return int
     */
    public function handle(): int
    {
        return $this->runTasks() ? 0 : 1;
    }

    /**
     * Run the given tasks
     *
     * @return bool
     */
    protected function runTasks(): bool
    {
        $tasks = $this->laravel->make(TasksResolver::class)
            ->setIO($this->input, $this->output)
            ->setData($this->mergeData())
            ->resolve($this->tasks());

        return $this->laravel->make(ConsoleTasker::class)->runTasks($tasks);
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
     * Retrieve the list of tasks to run
     *
     * @return array|string
     */
    protected function tasks(): array|string
    {
        return $this->tasks ?? $this->getTasksPath();
    }

    /**
     * Retrieve the path where tasks are defined
     *
     * @return string
     */
    protected function getTasksPath(): string
    {
        $command = class_basename(static::class);
        $directory = $this->config('tasks_directory');

        return rtrim($directory, '/') . "/{$command}/tasks.php";
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
