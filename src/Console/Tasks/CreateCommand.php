<?php

namespace Cerbero\ConsoleTasker\Console\Tasks;

use Cerbero\ConsoleTasker\Console\ParsedTask;
use Cerbero\ConsoleTasker\Console\TasksParser;
use Cerbero\ConsoleTasker\Tasks\FileCreator;
use Cerbero\ConsoleTasker\Concerns\ConfigAware;
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
     * Retrieve the path of the stub
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        return __DIR__ . '/../../../stubs/command.stub';
    }

    /**
     * Retrieve the fully qualified name of the file to create
     *
     * @return string|null
     */
    protected function getFullyQualifiedName(): ?string
    {
        $name = str_replace('/', '\\', $this->argument('name'));
        $namespace = $this->app->getNamespace();

        if (Str::startsWith($name, $namespace)) {
            return $name;
        }

        return "{$namespace}Console\Commands\\{$name}";
    }

    /**
     * Determine whether this task should run
     *
     * @return bool
     */
    public function shouldRun(): bool
    {
        return !file_exists($this->getPath());
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
        if ($this->option('command') == 'command:name') {
            return 'write command name and description';
        }

        return 'write command description';
    }

    /**
     * Retrieve the replacements to apply on the stub
     *
     * @return array
     */
    protected function getReplacements(): array
    {
        $parsedTasks = $this->option('tasks') ? $this->parser->parse($this->option('tasks')) : [];

        return [
            '{{ command }}' => $this->option('command'),
            '{{ tasks }}' => $this->getPaddedTasks($parsedTasks),
            '{{ namespaces }}' => $this->getNamespaces($parsedTasks),
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
            return str_repeat(' ', 12) . '//';
        }

        $paddedTasks = array_map(function (ParsedTask $task) {
            return str_repeat(' ', 12) . $task->name . '::class,';
        }, $parsedTasks);

        return implode(PHP_EOL, $paddedTasks);
    }

    /**
     * Retrieve the task namespaces
     *
     * @param array $parsedTasks
     * @return string|null
     */
    protected function getNamespaces(array $parsedTasks): ?string
    {
        if (empty($parsedTasks)) {
            return null;
        }

        $namespace = vsprintf('%s%s\%s', [
            $this->app->getNamespace(),
            str_replace('/', '\\', $this->config('tasks_directory')),
            $this->argument('name'),
        ]);

        return array_reduce($parsedTasks, function (string $carry, ParsedTask $task) use ($namespace) {
            return $carry .= "use {$namespace}\\{$task->name};" . PHP_EOL;
        }, '');
    }
}
