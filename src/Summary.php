<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Exceptions\StoppingTaskException;
use Cerbero\ConsoleTasker\Tasks\InvalidTask;
use Cerbero\ConsoleTasker\Tasks\Task;
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
     * @var Task[]
     */
    protected array $executedTasks = [];

    /**
     * The succeeded tasks.
     *
     * @var Task[]
     */
    protected array $succeededTasks = [];

    /**
     * The skipped tasks.
     *
     * @var Task[]
     */
    protected array $skippedTasks = [];

    /**
     * The failed tasks.
     *
     * @var Task[]
     */
    protected array $failedTasks = [];

    /**
     * The rolledback tasks.
     *
     * @var RollbackScope[]
     */
    protected array $rolledbackTasks = [];

    /**
     * The succeeded rollbacks.
     *
     * @var RollbackScope[]
     */
    protected array $succeededRollbacks = [];

    /**
     * The failed rollbacks.
     *
     * @var RollbackScope[]
     */
    protected array $failedRollbacks = [];

    /**
     * The invalid tasks.
     *
     * @var InvalidTask[]
     */
    protected array $invalidTasks = [];

    /**
     * The exceptions thrown.
     *
     * @var Throwable[]
     */
    protected array $exceptions = [];

    /**
     * The exceptions thrown on rollback.
     *
     * @var Throwable[]
     */
    protected array $rollbackExceptions = [];

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
        // call Summary::instance() instead
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
     * Add the given executed task
     *
     * @param Task $task
     * @return static
     */
    public function addExecutedTask(Task $task): static
    {
        $this->executedTasks[] = $task;

        return match (true) {
            $task->isInvalid() => $this->addInvalidTask($task),
            $task->succeeded() => $this->addSucceededTask($task),
            $task->wasSkipped() => $this->addSkippedTask($task),
            $task->failed() => $this->addFailedTask($task),
        };
    }

    /**
     * Add the given item to the invalid tasks
     *
     * @param InvalidTask $task
     * @return static
     */
    protected function addInvalidTask(InvalidTask $task): static
    {
        $this->invalidTasks[] = $task;

        return $this;
    }

    /**
     * Add the given succeeded task
     *
     * @param Task $task
     * @return static
     */
    protected function addSucceededTask(Task $task): static
    {
        $this->succeededTasks[] = $task;

        return $this;
    }

    /**
     * Add the given skipped task
     *
     * @param Task $task
     * @return static
     */
    protected function addSkippedTask(Task $task): static
    {
        $this->skippedTasks[] = $task;

        return $this;
    }

    /**
     * Add the given failed task
     *
     * @param Task $task
     * @return static
     */
    protected function addFailedTask(Task $task): static
    {
        $this->failedTasks[] = $task;

        if ($exception = $task->getException()) {
            $this->addException($exception);
        }

        return $this;
    }

    /**
     * Add the given rolledback task due to the provided failed task
     *
     * @param Task $task
     * @param Task $failedTask
     * @return static
     */
    public function addRolledbackTask(Task $task, Task $failedTask): static
    {
        $this->rolledbackTasks[] = new RollbackScope($task, $failedTask);

        if ($task->rolledback()) {
            $this->succeededRollbacks[] = new RollbackScope($task, $failedTask);
        } else {
            $this->failedRollbacks[] = new RollbackScope($task, $failedTask);

            if ($e = $task->getRollbackException()) {
                $this->rollbackExceptions[] = $e;
            }
        }

        return $this;
    }

    /**
     * Add the given exception
     *
     * @param Throwable $e
     * @return static
     */
    public function addException(Throwable $e): static
    {
        if (!$e instanceof StoppingTaskException) {
            $this->exceptions[] = $e;
        }

        return $this;
    }

    /**
     * Retrieve the executed tasks
     *
     * @return Task[]
     */
    public function getExecutedTasks(): array
    {
        return $this->executedTasks;
    }

    /**
     * Retrieve the succeeded tasks
     *
     * @return Task[]
     */
    public function getSucceededTasks(): array
    {
        return $this->succeededTasks;
    }

    /**
     * Retrieve the skipped tasks
     *
     * @return Task[]
     */
    public function getSkippedTasks(): array
    {
        return $this->skippedTasks;
    }

    /**
     * Retrieve the failed tasks
     *
     * @return Task[]
     */
    public function getFailedTasks(): array
    {
        return $this->failedTasks;
    }

    /**
     * Retrieve the rolledback tasks
     *
     * @return RollbackScope[]
     */
    public function getRolledbackTasks(): array
    {
        return $this->rolledbackTasks;
    }

    /**
     * Retrieve the succeeded rollbacks
     *
     * @return RollbackScope[]
     */
    public function getSucceededRollbacks(): array
    {
        return $this->succeededRollbacks;
    }

    /**
     * Retrieve the failed rollbacks
     *
     * @return RollbackScope[]
     */
    public function getFailedRollbacks(): array
    {
        return $this->failedRollbacks;
    }

    /**
     * Retrieve the invalid tasks
     *
     * @return InvalidTask[]
     */
    public function getInvalidTasks(): array
    {
        return $this->invalidTasks;
    }

    /**
     * Retrieve the exceptions thrown
     *
     * @return Throwable[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Retrieve the exceptions thrown on rollback
     *
     * @return Throwable[]
     */
    public function getRollbackExceptions(): array
    {
        return $this->rollbackExceptions;
    }

    /**
     * Retrieve the first exception thrown, if any
     *
     * @return Throwable|null
     */
    public function getFirstException(): ?Throwable
    {
        return $this->exceptions[0] ?? $this->rollbackExceptions[0] ?? null;
    }

    /**
     * Determine whether all tasks succeeded
     *
     * @return bool
     */
    public function succeeded(): bool
    {
        return count($this->executedTasks) == count($this->succeededTasks) + count($this->skippedTasks)
            && empty($this->failedTasks)
            && empty($this->rolledbackTasks)
            && empty($this->invalidTasks)
            && empty($this->exceptions)
            && empty($this->rollbackExceptions);
    }

    /**
     * Clear the summary instance
     *
     * @return void
     */
    public function clear(): void
    {
        $this->executedTasks = [];
        $this->succeededTasks = [];
        $this->skippedTasks = [];
        $this->failedTasks = [];
        $this->rolledbackTasks = [];
        $this->succeededRollbacks = [];
        $this->failedRollbacks = [];
        $this->invalidTasks = [];
        $this->exceptions = [];
        $this->rollbackExceptions = [];

        static::$instance = null;
    }
}
