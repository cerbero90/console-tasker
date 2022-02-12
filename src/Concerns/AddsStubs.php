<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Cerbero\ConsoleTasker\Tasks\Task;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * The trait to manipulate files by adding new stubs.
 *
 */
trait AddsStubs
{
    /**
     * Add the given stub before the last occurrence of the searched text
     *
     * @param string $search
     * @param string $path
     * @param array $replacements
     * @return static
     */
    public function addStubBeforeLast(string $search, string $path = '', array $replacements = []): static
    {
        $regex = "/{$search}(?!.*{$search})/s";

        return $this->addStubBeforeRegex($regex, $path, $replacements);
    }

    /**
     * Add the given stub before the provided regular expression
     *
     * @param string $regex
     * @param string $path
     * @param array $replacements
     * @return static
     */
    public function addStubBeforeRegex(string $regex, string $path = '', array $replacements = []): static
    {
        return $this->addStubByRegex($regex, $path, $replacements, function (string $stubContent, mixed $match) {
            return $stubContent . $match;
        });
    }

    /**
     * Add the given stub based on the provided regular expression
     *
     * @param string $regex
     * @param string $path
     * @param array $replacements
     * @param callable $callable
     * @return static
     */
    protected function addStubByRegex(string $regex, string $path, array $replacements, callable $callable): static
    {
        [$search, $replace] = $this->getData()->merge($replacements)->parseReplacements();
        $stubContent = str_replace($search, $replace, file_get_contents($this->stub($path)));

        $callback = function (array $matches) use ($callable, $stubContent) {
            return $callable($stubContent, ...$matches);
        };

        $content = preg_replace_callback($regex, $callback, file_get_contents($this->getPath()));

        file_put_contents($this->getPath(), $content);

        return $this;
    }

    /**
     * Retrieve the full path of the given stub
     *
     * @param string $stub
     * @return string
     */
    protected function stub(string $stub): string
    {
        if (file_exists($stub)) {
            return $stub;
        }

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $caller = Arr::first($backtrace, fn (array $trace) => is_subclass_of($trace['class'], Task::class));
        $path = (new ReflectionClass($caller['class']))->getFileName();

        if (empty($stub)) {
            $stub = (string) Str::of($caller['class'])->classBasename()->snake();
        }

        return dirname($path) . '/stubs/' . ltrim($stub, '/') . '.stub';
    }

    /**
     * Add the given stub after the last occurrence of the searched text
     *
     * @param string $search
     * @param string $path
     * @param array $replacements
     * @return static
     */
    public function addStubAfterLast(string $search, string $path = '', array $replacements = []): static
    {
        $regex = "/{$search}(?!.*{$search})/s";

        return $this->addStubAfterRegex($regex, $path, $replacements);
    }

    /**
     * Add the given stub after the provided regular expression
     *
     * @param string $regex
     * @param string $path
     * @param array $replacements
     * @return static
     */
    public function addStubAfterRegex(string $regex, string $path = '', array $replacements = []): static
    {
        return $this->addStubByRegex($regex, $path, $replacements, function (string $stubContent, mixed $match) {
            return $match . $stubContent;
        });
    }
}
