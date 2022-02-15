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
     * The task parent class.
     *
     * @var string
     */
    public string $parent;

    /**
     * Whether the task needs a stub.
     *
     * @var bool
     */
    public bool $needsStub;
}
