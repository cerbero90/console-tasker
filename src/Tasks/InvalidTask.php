<?php

namespace Cerbero\ConsoleTasker\Tasks;

/**
 * An invalid task.
 *
 */
class InvalidTask extends AbstractTask
{
    /**
     * The invalid task class.
     *
     * @var string
     */
    protected $class;

    /**
     * Construct the class
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
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
     * Set the invalid task class
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
     * @param AbstractTask|null $task
     * @return bool
     */
    public function shouldRollbackDueTo(?AbstractTask $task): bool
    {
        return false;
    }
}
