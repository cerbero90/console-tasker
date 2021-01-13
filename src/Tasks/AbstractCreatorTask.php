<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\ManipulatedFile;
use Illuminate\Container\Container;
use RuntimeException;

/**
 * The abstract creator task.
 *
 */
abstract class AbstractCreatorTask extends AbstractFilesManipulatorTask
{
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
        $file = $this->file($this->getPath());

        if ($reason = $this->needsManualUpdateTo()) {
            $file->needsManualUpdateTo($reason);
        }

        if ($this->canCreateFile($file)) {
            return $this->createFile($file);
        }

        $this->setError($this->getCreationError($file));

        return false;
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

        if (strpos($name, $namespace = Container::getInstance()->make('app')->getNamespace()) === 0) {
            $name = substr_replace($name, 'app/', 0, strlen($namespace));
        }

        return Container::getInstance()->make('app')->basePath(str_replace('\\', '/', $name)) . '.php';
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
     * @param ManipulatedFile $file
     * @return bool
     */
    protected function canCreateFile(ManipulatedFile $file): bool
    {
        if ($this->hasOption('force') && $this->option('force')) {
            return true;
        }

        return !file_exists($file->getPath());
    }

    /**
     * Retrieve the reason why the file could not be created
     *
     * @param ManipulatedFile $file
     * @return string
     */
    protected function getCreationError(ManipulatedFile $file): string
    {
        return 'The file ' . basename($file->getPath()) . ' already exists';
    }

    /**
     * Create the file
     *
     * @param ManipulatedFile $file
     * @return bool
     */
    protected function createFile(ManipulatedFile $file): bool
    {
        $path = $file->getPath();

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
}
