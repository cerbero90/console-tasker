<?php

namespace Cerbero\ConsoleTasker\Concerns;

/**
 * The trait to manipulate files by adding new lines.
 *
 */
trait AddsLines
{
    /**
     * Add the given line after the last occurrence of the searched line
     *
     * @param string $search
     * @param mixed $line
     * @return static
     */
    public function addLineAfterLast(string $search, mixed $line): static
    {
        $newline = PHP_EOL;
        $regex = "/([ \t]*).*{$search}.*{$newline}(?!.*{$search})/";

        return $this->addLineAfterRegex($regex, $line, 2);
    }

    /**
     * Add the given line after the provided regular expression
     *
     * @param string $regex
     * @param mixed $line
     * @param int $offset
     * @return static
     */
    public function addLineAfterRegex(string $regex, mixed $line, int $offset = 0): static
    {
        return $this->addLineByRegex($regex, $line, function ($line, $matchedLine, $spaces) {
            return $matchedLine . $spaces . $line . PHP_EOL;
        }, $offset);
    }

    /**
     * Add the given line based on the provided regular expression
     *
     * @param string $regex
     * @param mixed $line
     * @param callable $callable
     * @param int $offset
     * @return static
     */
    protected function addLineByRegex(string $regex, mixed $line, callable $callable, int $offset = 0): static
    {
        $callback = function (array $matches) use ($line, $callable, $offset) {
            $value = is_callable($line) ? $line(...array_slice($matches, $offset)) : $line;
            return $callable($value, ...$matches);
        };

        $content = preg_replace_callback($regex, $callback, file_get_contents($this->getPath()));

        file_put_contents($this->getPath(), $content);

        return $this;
    }

    /**
     * Add the given line after the last occurrence of the provided docblock comment
     *
     * @param string $search
     * @param mixed $line
     * @return static
     */
    public function addLineAfterLastDocblock(string $search, mixed $line): static
    {
        $newline = PHP_EOL;
        $regex = "/([ \t\*]*).*{$search}.*{$newline}(?!.*{$search})/";

        return $this->addLineAfterRegex($regex, $line, 2);
    }
}
