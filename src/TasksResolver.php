<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Concerns\DataAware;
use Cerbero\ConsoleTasker\Concerns\IOAware;
use Cerbero\ConsoleTasker\Tasks\InvalidTask;
use Cerbero\ConsoleTasker\Tasks\Task;
use Illuminate\Contracts\Foundation\Application;
use Throwable;
use Traversable;

/**
 * The tasks resolver.
 *
 */
class TasksResolver
{
    use DataAware;
    use IOAware;

    /**
     * Instantiate the class.
     *
     * @param Application $app
     */
    public function __construct(protected Application $app)
    {
    }

    /**
     * Retrieve the resolved tasks
     *
     * @param array|string $tasks
     * @return Task[]
     */
    public function resolve(array|string $tasks): iterable
    {
        if (is_array($tasks)) {
            foreach ($tasks as $task) {
                yield $this->resolveClass($task);
            }
        } else {
            yield from $this->resolveTasksFromPath($tasks);
        }
    }

    /**
     * Retrieve the resolved task by the given class
     *
     * @param string $class
     * @return Task
     */
    protected function resolveClass(string $class): Task
    {
        try {
            $task = $this->app->make($class);
        } catch (Throwable $e) {
            $task = new InvalidTask($class, $e);
        }

        if (!$task instanceof Task) {
            $task = new InvalidTask($class);
        }

        return $this->populateTask($task);
        // return State::instance()->fill($task);
    }

    /**
     * Retrieve the given task populated with the needed information
     *
     * @param Task $task
     * @return Task
     */
    protected function populateTask(Task $task): Task
    {
        return $task->setIO($this->input, $this->output)->setApp($this->app)->setData($this->getData());
    }

    /**
     * Resolve tasks from the given path
     *
     * @param string $__path
     * @return Traversable
     */
    protected function resolveTasksFromPath(string $__path): Traversable
    {
        extract($this->getData()->getAttributes(), EXTR_SKIP);

        require $__path;

        foreach (TasksCollector::instance() as $task) {
            yield $this->populateTask($task);
        }
    }
}
