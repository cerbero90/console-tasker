<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\File;
use RuntimeException;

/**
 * The abstract creator task.
 *
 */
abstract class FileCreator extends FilesEditor
{
    /**
     * The file to create.
     *
     * @var File
     */
    protected $file;

    /**
     * Whether this task should be skipped if the file to create already exists.
     *
     * @var bool
     */
    protected $shouldBeSkippedIfFileExists = true;

    /**
     * Retrieve the path of the stub
     *
     * @return string
     */
    abstract protected function getStubPath(): string;

    /**
     * Run the task
     *
     * @return mixed
     */
    public function run()
    {
        $this->file = $this->file($this->getPath());

        if ($reason = $this->needsManualUpdateTo()) {
            $this->file->needsManualUpdateTo($reason);
        }

        if ($this->canCreateFile()) {
            return $this->createFile();
        }

        if (!$this->shouldBeSkippedIfFileExists()) {
            $this->error = $this->getCreationError();
            return false;
        }
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
        return null;
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
     * Retrieve the reason why the file could not be created
     *
     * @return string
     */
    protected function getCreationError(): string
    {
        return 'the file ' . basename($this->getPath()) . ' already exists';
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
            @mkdir(dirname($path), 0777, true);
        }

        $replacements = array_merge($this->getDefaultReplacements(), $this->getReplacements());
        $stubContent = file_get_contents($this->getStubPath());
        $content = str_replace(array_keys($replacements), array_values($replacements), $stubContent);

        return file_put_contents($path, $content) !== false;
    }

    /**
     * Retrieve the default replacements to apply on the stub
     *
     * @return array
     */
    protected function getDefaultReplacements(): array
    {
        $qualified = $this->getFullyQualifiedName();
        $class = class_basename($qualified);
        $namespace = substr($qualified, 0, strrpos($qualified, $class) - 1);

        return [
            'DummyClass' => $class,
            '{{ class }}' => $class,
            'DummyNamespace' => $namespace,
            '{{ namespace }}' => $namespace,
        ];
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
        return $this->getCreationError();
    }
}
