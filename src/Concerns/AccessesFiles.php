<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Cerbero\ConsoleTasker\File;
use ReflectionClass;

/**
 * The trait to access files.
 *
 */
trait AccessesFiles
{
    /**
     * The files being manipulated.
     *
     * @var File[]
     */
    protected array $files = [];

    /**
     * Retrieve the files being manipulated
     *
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Retrieve an instance of file by the given path
     *
     * @param string $path
     * @return File
     */
    protected function file(string $path): File
    {
        return $this->files[] = new File($path);
    }

    /**
     * Retrieve an instance of file by the given class
     *
     * @param string $class
     * @return File
     */
    protected function class(string $class): File
    {
        $reflection = new ReflectionClass($class);

        return $this->file($reflection->getFileName());
    }

    /**
     * Retrieve an instance of file by the given file in the base path
     *
     * @param string $file
     * @return File
     */
    protected function base(string $file): File
    {
        $path = $this->app->basePath($file);

        return $this->file($path);
    }

    /**
     * Retrieve an instance of file by the given config file
     *
     * @param string $config
     * @return File
     */
    protected function config(string $config): File
    {
        $path = $this->app->configPath($config);

        return $this->file($path);
    }

    /**
     * Retrieve an instance of file by the given database file
     *
     * @param string $file
     * @return File
     */
    protected function database(string $file): File
    {
        $path = $this->app->databasePath($file);

        return $this->file($path);
    }

    /**
     * Retrieve an instance of file by the given file in the public path
     *
     * @param string $file
     * @return File
     */
    protected function public(string $file): File
    {
        return $this->base('public/' . ltrim($file, '/'));
    }

    /**
     * Retrieve an instance of file by the given resource
     *
     * @param string $resource
     * @return File
     */
    protected function resource(string $resource): File
    {
        $path = $this->app->resourcePath($resource);

        return $this->file($path);
    }

    /**
     * Retrieve an instance of file by the given route file
     *
     * @param string $route
     * @return File
     */
    protected function route(string $route): File
    {
        return $this->base('routes/' . ltrim($route, '/'));
    }

    /**
     * Retrieve an instance of file by the given file in the storage path
     *
     * @param string $file
     * @return File
     */
    protected function storage(string $file): File
    {
        $path = $this->app->storagePath($file);

        return $this->file($path);
    }

    /**
     * Retrieve an instance of file by the given test
     *
     * @param string $test
     * @return File
     */
    protected function test(string $test): File
    {
        return $this->base('tests/' . ltrim($test, '/'));
    }
}
