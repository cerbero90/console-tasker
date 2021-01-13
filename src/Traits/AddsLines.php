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
     * @param mixed $lineToAdd
     * @return self
     */
    public function addLineAfterLast(string $search, $lineToAdd): self
    {
        $newline = PHP_EOL;
        $regex = "/([ \t]*).*{$search}.*{$newline}(?!.*{$search})/";

        return $this->addLineAfterRegex($regex, $lineToAdd);
    }

    /**
     * Add the given line after the provided regular expression
     *
     * @param string $regex
     * @param mixed $lineToAdd
     * @return self
     */
    public function addLineAfterRegex(string $regex, $lineToAdd): self
    {
        $callback = function (array $matches) use ($lineToAdd) {
            $value = is_callable($lineToAdd) ? $lineToAdd($matches) : $lineToAdd;
            return $matches[0] . $matches[1] . $value . PHP_EOL;
        };

        $content = preg_replace_callback($regex, $callback, file_get_contents($this->getPath()));

        file_put_contents($this->getPath(), $content);

        return $this;
    }

    /**
     * Add the given line after the last occurrence of the provided docblock comment
     *
     * @param string $search
     * @param mixed $lineToAdd
     * @return self
     */
    public function addLineAfterLastDocblockComment(string $search, $lineToAdd): self
    {
        $newline = PHP_EOL;
        $regex = "/([ \t\*]*).*{$search}.*{$newline}(?!.*{$search})/";

        return $this->addLineAfterRegex($regex, $lineToAdd);
    }
}
