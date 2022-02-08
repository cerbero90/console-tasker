<?php

namespace Cerbero\ConsoleTasker\Concerns;

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
     * @return self
     */
    public function addStubBeforeLast(string $search, string $path, array $replacements = []): static
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
     * @return self
     */
    public function addStubBeforeRegex(string $regex, string $path, array $replacements = []): static
    {
        return $this->addStubByRegex($regex, $path, $replacements, function ($stubContent, $matches) {
            return $stubContent . $matches[0];
        });
    }

    /**
     * Add the given stub based on the provided regular expression
     *
     * @param string $regex
     * @param string $path
     * @param array $replacements
     * @param callable $callable
     * @return self
     */
    public function addStubByRegex(string $regex, string $path, array $replacements, callable $callable): static
    {
        $stubContent = str_replace(array_keys($replacements), array_values($replacements), file_get_contents($path));

        $callback = function (array $matches) use ($callable, $stubContent) {
            return $callable($stubContent, $matches);
        };

        $content = preg_replace_callback($regex, $callback, file_get_contents($this->getPath()));

        file_put_contents($this->getPath(), $content);

        return $this;
    }
}
