<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Illuminate\Support\Str;
use ReflectionClass;
use RuntimeException;

/**
 * The abstract creator task.
 *
 */
abstract class FileCreator extends FilesEditor
{
    /**
     * Whether the task needs a stub to work.
     *
     * @var bool
     */
    protected static bool $needsStub = true;

    /**
     * Whether this task should be skipped if the file to create already exists.
     *
     * @var bool
     */
    protected bool $shouldBeSkippedIfFileExists = true;

    /**
     * The reason why the created file needs to be updated.
     *
     * @var string|null
     */
    protected ?string $manualUpdateReason = null;

    /**
     * Run the task
     *
     * @return mixed
     */
    public function run()
    {
        $file = $this->file($this->getPath());

        if ($reason = $this->needsManualUpdateTo()) {
            $file->needsManualUpdateTo($reason);
        }

        if ($this->canCreateFile()) {
            return $this->createFile();
        }

        return $this->shouldBeSkippedIfFileExists();
    }

    /**
     * Retrieve the path of the file to create
     *
     * @return string
     */
    protected function getPath(): string
    {
        if (is_null($name = $this->getFullyQualifiedName())) {
            throw new RuntimeException('Please provide a path or a fully qualified name for the file to create');
        }

        if (strpos($name, $namespace = $this->app->getNamespace()) === 0) {
            $name = substr_replace($name, 'app/', 0, strlen($namespace));
        }

        return $this->app->basePath(str_replace('\\', '/', $name)) . '.php';
    }

    /**
     * Retrieve the fully qualified name of the file to create
     *
     * @return string|null
     */
    protected function getFullyQualifiedName(): ?string
    {
        return null;
    }

    /**
     * Retrieve the reason why the file needs to be updated manually
     *
     * @return string|null
     */
    public function needsManualUpdateTo(): ?string
    {
        return $this->manualUpdateReason;
    }

    /**
     * Determine whether the file can be created
     *
     * @return bool
     */
    protected function canCreateFile(): bool
    {
        if ($this->hasOption('force') && $this->option('force')) {
            return true;
        }

        return !file_exists($this->getPath());
    }

    /**
     * Create the file
     *
     * @return bool
     */
    protected function createFile(): bool
    {
        $path = $this->getPath();

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        [$search, $replace] = $this->parseReplacements();
        $stubContent = file_get_contents($this->getStubPath());
        $content = str_replace($search, $replace, $stubContent);

        return file_put_contents($path, $content) !== false;
    }

    /**
     * Retrieve the parsed replacements to apply on the stub
     *
     * @return array
     */
    protected function parseReplacements(): array
    {
        $qualified = (string) $this->getFullyQualifiedName();
        $default = [
            'class' => class_basename($qualified),
            'namespace' => substr($qualified, 0, strrpos($qualified, '\\')),
        ];

        return $this->data->add($default)->merge($this->getReplacements())->parseReplacements();
    }

    /**
     * Retrieve the replacements to apply on the stub
     *
     * @return array
     */
    protected function getReplacements(): array
    {
        return [];
    }

    /**
     * Retrieve the path of the stub
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        $path = (new ReflectionClass($this))->getFileName();

        return dirname($path) . '/stubs/' . Str::of(static::class)->classBasename()->snake() . '.stub';
    }

    /**
     * Determine whether this task should run
     *
     * @return bool
     */
    public function shouldRun(): bool
    {
        return !file_exists($this->getPath()) || !$this->shouldBeSkippedIfFileExists();
    }

    /**
     * Determine whether this task should be skipped if the file to create already exists
     *
     * @return bool
     */
    protected function shouldBeSkippedIfFileExists(): bool
    {
        return $this->shouldBeSkippedIfFileExists;
    }

    /**
     * Retrieve the reason why this task should not run
     *
     * @return string|null
     */
    public function getSkippingReason(): ?string
    {
        return $this->getFailureReason();
    }

    /**
     * Retrieve the reason why this task failed
     *
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason ?: 'the file ' . basename($this->getPath()) . ' already exists';
    }
}
