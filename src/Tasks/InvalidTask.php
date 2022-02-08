<?php

namespace Cerbero\ConsoleTasker\Tasks;

/**
 * An invalid task.
 *
 */
class InvalidTask extends Task
{
    /**
     * Instantiate the class
     *
     * @param string $class
     */
    public function __construct(protected string $class)
    {
    }

    /**
     * Run the task
     *
     * @return mixed
     */
    public function run()
    {
        return false;
    }

    /**
     * Retrieve the invalid task class
     *
     * @param string $class
     * @return self
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Retrieve this task purpose
     *
     * @return string
     */
    public function getPurpose(): string
    {
        return class_basename($this->class);
    }

    /**
     * Retrieve the error that caused this task to fail
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        if ($e = $this->getException()) {
            return $e->getMessage();
        }

        return "The item [{$this->class}] is not a valid task";
    }

    /**
     * Determine whether this task should rollback if the given task fails
     *
     * @param Task $task
     * @return bool
     */
    public function shouldRollbackDueTo(Task $task): bool
    {
        return false;
    }
}
