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
     * The list of parsed tasks.
     *
     * @var ParsedTask[]
     */
    protected array $parsedTasks;

    /**
     * The parsed task to generate.
     *
     * @var ParsedTask
     */
    protected ParsedTask $parsedTask;

    /**
     * The reason why the created file needs to be updated.
     *
     * @var string|null
     */
    protected ?string $manualUpdateReason = 'implement task logic';

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
        $this->parsedTasks = $this->parser->parse($this->tasks ?: '');

        foreach ($this->parsedTasks as $parsedTask) {
            $this->parsedTask = $parsedTask;

            if (parent::run() === false) {
                return $this->failWithReason('Unable to create the task ' . $parsedTask->name);
            } elseif (!$this->createStub()) {
                return $this->failWithReason('Unable to create the stub for ' . $parsedTask->name);
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
        if (!$this->parsedTask->needsStub) {
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
     * Retrieve the path of the stub
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        $stub = Str::of($this->parsedTask->parent)->classBasename()->snake() . '.stub';
        $customPath = $this->app->basePath('stubs/console-tasker/');

        return match (true) {
            file_exists($path = $customPath . $stub) => $path,
            file_exists($path = __DIR__ . "/stubs/{$stub}") => $path,
            file_exists($path = $customPath . 'task.stub') => $path,
            default => __DIR__ . "/stubs/task.stub",
        };
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
     * Retrieve the reason why this task succeeded
     *
     * @return string|null
     */
    public function getSuccessReason(): ?string
    {
        if (count($this->parsedTasks) == 1) {
            return "the task {$this->parsedTask->name} was created successfully";
        }

        return 'the tasks were created successfully';
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
