<?php

namespace Cerbero\ConsoleTasker\Console;

/**
 * The parsed task.
 *
 */
class ParsedTask
{
    /**
     * The task name.
     *
     * @var string
     */
    public string $name;

    /**
     * The parent task class.
     *
     * @var string
     */
    public string $parentClass;
}
