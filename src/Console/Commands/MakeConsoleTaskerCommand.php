<?php

namespace Cerbero\ConsoleTasker\Console\Commands;

use Cerbero\ConsoleTasker\Concerns\RunsTasks;
use Cerbero\ConsoleTasker\Console\Tasks\CreateCommand;
use Cerbero\ConsoleTasker\Console\Tasks\CreateCommandTasks;
use Illuminate\Console\Command;

/**
 * The Artisan command to generate console taskers.
 *
 */
class MakeConsoleTaskerCommand extends Command
{
    use RunsTasks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:console-tasker
                            {name : The name of the command}
                            {--c|command=command:name : The terminal command that should be assigned}
                            {--t|tasks= : Comma-separated list of tasks to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new console tasker';

    /**
     * The tasks to run.
     *
     * @var string[]
     */
    protected array $tasks = [CreateCommand::class, CreateCommandTasks::class];

    /**
     * Retrieve the data for tasks and stubs
     *
     * @return array
     */
    protected function data(): array
    {
        return [
            'name' => $this->argument('name'),
            'command' => $this->option('command'),
            'tasks' => $this->option('tasks'),
        ];
    }
}
