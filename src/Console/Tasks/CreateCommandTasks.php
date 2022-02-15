<?php

namespace Cerbero\ConsoleTasker\Console\Tasks;

use Cerbero\ConsoleTasker\Concerns\ConfigAware;
use Cerbero\ConsoleTasker\Console\ParsedTask;
use Cerbero\ConsoleTasker\Console\TasksParser;
use Cerbero\ConsoleTasker\Tasks\FileCreator;
use Illuminate\Support\Str;

/**
 * The task to create the command tasks.
 *
 */
class CreateCommandTasks extends FileCreator
{
    use ConfigAware;

    /**
     * The parsed task to generate.
     *
     * @var ParsedTask
     */
    protected ParsedTask $parsedTask;

    /**
     * Instantiate the class.
     *
     * @param TasksParser $parser
     */
    public function __construct(protected TasksParser $parser)
    {
    }

    /**
     * Run the task
     *
     * @return mixed
     */
    public function run()
    {
        $parsedTasks = $this->parser->parse($this->tasks);

        foreach ($parsedTasks as $parsedTask) {
            $this->parsedTask = $parsedTask;

            $succeeded = parent::run() !== false && $this->createStub();

            if (!$succeeded) {
                return false;
            }
        }
    }

    /**
     * Create stub if needed
     *
     * @return bool
     */
    protected function createStub(): bool
    {
        if ($this->parsedTask->parent != FileCreator::class && !$this->parsedTask->needsStub) {
            return true;
        }

        $path = dirname($this->getPath()) . '/stubs/' . Str::snake($this->parsedTask->name) . '.stub';
        $this->file($path)->needsManualUpdateTo('add stub content');

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        return file_put_contents($path, '') !== false;
    }

    /**
     * Determine whether this task should run
     *
     * @return bool
     */
    public function shouldRun(): bool
    {
        return $this->tasks !== null;
    }

    /**
     * Retrieve the reason why this task should not run
     *
     * @return string|null
     */
    public function getSkippingReason(): ?string
    {
        return 'tasks to generate were not specified';
    }

    /**
     * Retrieve the path of the stub
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        $stub = Str::of($this->parsedTask->parent)->classBasename()->snake() . '.stub';
        $customPath = $this->app->basePath("stubs/console-tasker/{$stub}");

        return file_exists($customPath) ? $customPath : __DIR__ . "/stubs/{$stub}";
    }

    /**
     * Retrieve the fully qualified name of the file to create
     *
     * @return string|null
     */
    protected function getFullyQualifiedName(): ?string
    {
        return (string) Str::of($this->config('tasks_directory'))
            ->replace([$this->app->basePath('app/'), '/'], [$this->app->getNamespace(), '\\'])
            ->append('\\', $this->data->name, '\\', $this->parsedTask->name);
    }

    /**
     * Retrieve the reason why the file needs to be updated manually
     *
     * @return string|null
     */
    public function needsManualUpdateTo(): ?string
    {
        return 'implement task logic';
    }

    /**
     * Retrieve the replacements to apply on the stub
     *
     * @return array
     */
    protected function getReplacements(): array
    {
        return [
            'purpose' => Str::snake($this->parsedTask->name, ' '),
        ];
    }
}
