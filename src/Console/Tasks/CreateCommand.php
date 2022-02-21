<?php

namespace Cerbero\ConsoleTasker\Console\Tasks;

use Cerbero\ConsoleTasker\Concerns\ConfigAware;
use Cerbero\ConsoleTasker\Concerns\RunsTasks;
use Cerbero\ConsoleTasker\Console\ParsedTask;
use Cerbero\ConsoleTasker\Console\TasksParser;
use Cerbero\ConsoleTasker\Tasks\FileCreator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * The task to create the Artisan command.
 *
 */
class CreateCommand extends FileCreator
{
    use ConfigAware;

    /**
     * Instantiate the class.
     *
     * @param TasksParser $parser
     */
    public function __construct(protected TasksParser $parser)
    {
    }

    /**
     * Retrieve the fully qualified name of the file to create
     *
     * @return string|null
     */
    protected function getFullyQualifiedName(): ?string
    {
        $name = str_replace('/', '\\', $this->data->name);
        $namespace = $this->app->getNamespace();

        if (Str::startsWith($name, $namespace)) {
            return $name;
        }

        return "{$namespace}Console\Commands\\{$name}";
    }

    /**
     * Retrieve the reason why this task should not run
     *
     * @return string|null
     */
    public function getSkippingReason(): ?string
    {
        return 'the command already exists';
    }

    /**
     * Retrieve the reason why the file needs to be updated manually
     *
     * @return string|null
     */
    public function needsManualUpdateTo(): ?string
    {
        if ($this->command == 'command:name') {
            return 'define command signature and description';
        }

        return 'define command description';
    }

    /**
     * Retrieve the replacements to apply on the stub
     *
     * @return array
     */
    protected function getReplacements(): array
    {
        $parsedTasks = $this->tasks ? $this->parser->parse($this->tasks) : [];

        return [
            'tasks' => $this->getPaddedTasks($parsedTasks),
            'useStatements' => $this->getUseStatements(),
        ];
    }

    /**
     * Retrieve the command tasks with padding
     *
     * @param array $parsedTasks
     * @return string
     */
    protected function getPaddedTasks(array $parsedTasks): string
    {
        if (empty($parsedTasks)) {
            return str_repeat(' ', 8) . '// @todo list tasks to run';
        }

        return collect($parsedTasks)
            ->map(fn (ParsedTask $task) => str_repeat(' ', 8) . 'Tasks\\' . $task->name . '::class,')
            ->implode(PHP_EOL);
    }

    /**
     * Retrieve the use statements
     *
     * @return string|null
     */
    protected function getUseStatements(): ?string
    {
        $namespace = Str::of($this->config('tasks_directory'))
            ->replace([$this->app->basePath('app/'), '/'], [$this->app->getNamespace(), '\\'])
            ->append('\\', $this->data->name);

        return collect([Command::class, RunsTasks::class, $namespace . ' as Tasks'])
            ->sort()
            ->map(fn (string $class) => "use {$class};")
            ->implode(PHP_EOL);
    }

    /**
     * Retrieve the path of the stub
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        $customPath = $this->app->basePath('stubs/console-tasker/create_command.stub');

        return file_exists($customPath) ? $customPath : __DIR__ . '/stubs/create_command.stub';
    }
}
