<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Tasks\AbstractTask;
use Throwable;

/**
 * The tasks summary.
 *
 */
class Summary
{
    /**
     * The executed tasks.
     *
     * @var AbstractTask[]
     */
    protected $executedTasks = [];

    /**
     * The succeeded tasks.
     *
     * @var AbstractTask[]
     */
    protected $succeededTasks = [];

    /**
     * The failed tasks.
     *
     * @var AbstractTask[]
     */
    protected $failedTasks = [];

    /**
     * The rolledback tasks.
     *
     * @var AbstractTask[]
     */
    protected $rolledbackTasks = [];

    /**
     * The invalid tasks.
     *
     * @var array
     */
    protected $invalidTasks = [];

    /**
     * The first exception ever thrown.
     *
     * @var Throwable|null
     */
    protected $firstException;

    /**
     * The summary instance.
     *
     * @var self
     */
    protected static $instance;

    /**
     * Disable class instantiation in favor of singleton
     *
     */
    protected function __construct()
    {
        // call Summary::instance() instead
    }

    /**
     * Retrieve the summary instance
     *
     * @return self
     */
    public static function instance(): self
    {
        return static::$instance = static::$instance ?: new static();
    }

    /**
     * Retrieve the executed tasks
     *
     * @return array
     */
    public function getExecutedTasks(): array
    {
        return $this->executedTasks;
    }

    /**
     * Retrieve the succeeded tasks
     *
     * @return array
     */
    public function getSucceededTasks(): array
    {
        return $this->succeededTasks;
    }

    /**
     * Retrieve the failed tasks
     *
     * @return array
     */
    public function getFailedTasks(): array
    {
        return $this->failedTasks;
    }

    /**
     * Retrieve the rolledback tasks
     *
     * @return array
     */
    public function getRolledbackTasks(): array
    {
        return $this->rolledbackTasks;
    }

    /**
     * Retrieve the invalid tasks
     *
     * @return array
     */
    public function getInvalidTasks(): array
    {
        return $this->invalidTasks;
    }

    /**
     * Retrieve the first exception thrown, if any
     *
     * @return Throwable|null
     */
    public function getFirstException(): ?Throwable
    {
        return $this->firstException;
    }

    /**
     * Add the given task to the successfully executed tasks
     *
     * @param AbstractTask $task
     * @return self
     */
    public function addExecutedTask(AbstractTask $task): self
    {
        $this->executedTasks[] = $task;

        if ($task->succeeded()) {
            $this->succeededTasks[] = $task;
        } else {
            $this->failedTasks[] = $task;
            $this->firstException = $this->firstException ?: $task->getException();
        }

        return $this;
    }

    /**
     * Add the given rolledback task due to the provided failed task
     *
     * @param AbstractTask $task
     * @param AbstractTask $failedTask
     * @return self
     */
    public function addRolledbackTask(AbstractTask $task, AbstractTask $failedTask): self
    {
        $this->rolledbackTasks[] = new RollbackScope($task, $failedTask);

        return $this;
    }

    /**
     * Add the given item to the invalid tasks
     *
     * @param AbstractTask $task
     * @return self
     */
    public function addInvalidTask(AbstractTask $task): self
    {
        $this->invalidTasks[] = $task;

        return $this;
    }

    /**
     * Determine whether all tasks succeeded
     *
     * @return bool
     */
    public function succeeded(): bool
    {
        return empty($this->failedTasks)
            && empty($this->rolledbackTasks)
            && count($this->executedTasks) == count($this->succeededTasks);
    }

    /**
     * Clear the summary instance
     *
     * @return void
     */
    public function clear(): void
    {
        static::$instance = null;

        $this->executedTasks = [];
        $this->succeededTasks = [];
        $this->failedTasks = [];
        $this->rolledbackTasks = [];
        $this->invalidTasks = [];
    }
}
