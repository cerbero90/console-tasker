<?php

namespace Cerbero\ConsoleTasker\Exceptions;

use Cerbero\ConsoleTasker\Tasks\AbstractTask;
use RuntimeException;

/**
 * The exception stopping the execution of next tasks.
 *
 */
class StoppingTaskException extends RuntimeException
{
    /**
     * The failed task.
     *
     * @var AbstractTask
     */
    protected $task;

    /**
     * Instantiate the class.
     *
     * @param AbstractTask $task
     */
    public function __construct(AbstractTask $task)
    {
        parent::__construct($task->getError());

        $this->task = $task;
    }

    /**
     * Retrieve the failed task
     *
     * @return AbstractTask
     */
    public function getTask(): AbstractTask
    {
        return $this->task;
    }
}
