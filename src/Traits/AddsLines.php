<?php

namespace Cerbero\ConsoleTasker\Traits;

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
     * @return self
     */
    public function addLineAfterLast(string $search, $line): self
    {
        $newline = PHP_EOL;
        $regex = "/([ \t]*).*{$search}.*{$newline}(?!.*{$search})/";

        return $this->addLineAfterRegex($regex, $line);
    }

    /**
     * Add the given line after the provided regular expression
     *
     * @param string $regex
     * @param mixed $line
     * @return self
     */
    public function addLineAfterRegex(string $regex, $line): self
    {
        return $this->addLineByRegex($regex, $line, function ($line, $matches) {
            return $matches[0] . $matches[1] . $line . PHP_EOL;
        });
    }

    /**
     * Add the given line based on the provided regular expression
     *
     * @param string $regex
     * @param mixed $line
     * @param callable $callable
     * @return self
     */
    public function addLineByRegex(string $regex, $line, callable $callable): self
    {
        $callback = function (array $matches) use ($line, $callable) {
            $value = is_callable($line) ? $line($matches) : $line;
            return $callable($value, $matches);
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
     * @return self
     */
    public function addLineAfterLastDocblockComment(string $search, $line): self
    {
        $newline = PHP_EOL;
        $regex = "/([ \t\*]*).*{$search}.*{$newline}(?!.*{$search})/";

        return $this->addLineAfterRegex($regex, $line);
    }
}
