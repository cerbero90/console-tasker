<?php

namespace Cerbero\ConsoleTasker\Console\Tasks;

use Cerbero\ConsoleTasker\Console\ParsedTask;
use Cerbero\ConsoleTasker\Console\TasksParser;
use Cerbero\ConsoleTasker\Tasks\AbstractCreatorTask;
use Cerbero\ConsoleTasker\Traits\ConfigAware;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

/**
 * The task to create the Artisan command.
 *
 */
class CreateCommand extends AbstractCreatorTask
{
    use ConfigAware;

    /**
     * The tasks parser.
     *
     * @var TasksParser
     */
    protected $parser;

    /**
     * Instantiate the class.
     *
     * @param TasksParser $parser
     */
    public function __construct(TasksParser $parser)
    {
        $this->parser = $parser;
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
        $namespace = Container::getInstance()->make('app')->getNamespace();

        if (Str::startsWith($name, $namespace)) {
            return $name;
        }

        return "{$namespace}Console\Commands\\{$name}";
    }

    /**
     * Retrieve the reason why the file needs to be updated manually
     *
     * @return string|null
     */
    public function needsManualUpdateTo(): ?string
    {
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
            Container::getInstance()->make('app')->getNamespace(),
            str_replace('/', '\\', $this->config('tasks_directory')),
            $this->argument('name'),
        ]);

        return array_reduce($parsedTasks, function (string $carry, ParsedTask $task) use ($namespace) {
            return $carry .= "use {$namespace}\\{$task->name};" . PHP_EOL;
        }, '');
    }
}
