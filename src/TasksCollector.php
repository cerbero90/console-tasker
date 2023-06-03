<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Tasks\Task;
use IteratorAggregate;
use Traversable;

/**
 * The tasks collector.
 *
 */
class TasksCollector implements IteratorAggregate
{
    /**
     * The collected tasks.
     *
     * @var Task[]
     */
    protected array $tasks = [];

    /**
     * The summary instance.
     *
     * @var self|null
     */
    protected static ?self $instance;

    /**
     * Disable class instantiation in favor of singleton
     *
     */
    protected function __construct()
    {
        // call TasksCollector::instance() instead
    }

    /**
     * Retrieve the summary instance
     *
     * @return static
     */
    public static function instance(): static
    {
        return static::$instance ??= new static();
    }

    /**
     * Collect the given task
     *
     * @param Task $task
     * @return static
     */
    public function collect(Task $task): static
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Clear the collector instance
     *
     * @return void
     */
    public function clear(): void
    {
        $this->tasks = [];

        static::$instance = null;
    }

    /**
     * Retrieve the collected tasks
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        foreach ($this->tasks as $task) {
            yield $task;
        }
    }
}
