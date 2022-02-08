<?php

namespace Cerbero\ConsoleTasker\Exceptions;

use Cerbero\ConsoleTasker\Tasks\Task;
use RuntimeException;

/**
 * The exception stopping the execution of next tasks.
 *
 */
class StoppingTaskException extends RuntimeException
{
    /**
     * Instantiate the class.
     *
     * @param Task $task
     */
    public function __construct(protected Task $task)
    {
        parent::__construct($task->getError());
    }

    /**
     * Retrieve the failed task
     *
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }
}
