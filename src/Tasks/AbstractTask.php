<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Traits\IOAware;
use Illuminate\Support\Str;
use Throwable;

/**
 * The abstract task.
 *
 */
abstract class AbstractTask
{
    use IOAware;

    /**
     * Whether the task succeeded.
     *
     * @var bool
     */
    protected $succeeded = false;

    /**
     * The error that caused this task to fail.
     *
     * @var string|null
     */
    protected $error = null;

    /**
     * The exception that caused this task to fail.
     *
     * @var Throwable|null
     */
    protected $exception = null;

    /**
     * The exception that caused this task rollback to fail.
     *
     * @var Throwable|null
     */
    protected $rollbackException = null;

    /**
     * Whether this task executed the rollback logic.
     *
     * @var bool
     */
    protected $ranRollback = false;

    /**
     * Whether this task was successfully rolled back.
     *
     * @var bool
     */
    protected $rolledback = false;

    /**
     * Run the task
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * Retrieve this task purpose
     *
     * @return string
     */
    public function getPurpose(): string
    {
        $class = class_basename(static::class);

        return Str::snake($class, ' ');
    }

    /**
     * Determine whether this task succeeded
     *
     * @return bool
     */
    public function succeeded(): bool
    {
        return $this->succeeded;
    }

    /**
     * Set the result of this step
     *
     * @param bool $result
     * @return self
     */
    public function setResult(bool $result): self
    {
        $this->succeeded = $result;

        return $this;
    }

    /**
     * Retrieve the error that caused this task to fail
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Set the error that caused this task to fail
     *
     * @param string $error
     * @return self
     */
    public function setError(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Retrieve the exception that caused this task to fail
     *
     * @return Throwable|null
     */
    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    /**
     * Set the exception that caused this task to fail
     *
     * @param Throwable $exception
     * @return self
     */
    public function setException(Throwable $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * Retrieve the exception that caused this task rollback to fail
     *
     * @return Throwable|null
     */
    public function getRollbackException(): ?Throwable
    {
        return $this->rollbackException;
    }

    /**
     * Set the exception that caused this task rollback to fail
     *
     * @param Throwable $rollbackException
     * @return self
     */
    public function setRollbackException(Throwable $rollbackException): self
    {
        $this->rollbackException = $rollbackException;

        return $this;
    }

    /**
     * Determine whether this task should run
     *
     * @return bool
     */
    public function shouldRun(): bool
    {
        return true;
    }

    /**
     * Retrieve the reason why this task should not run
     *
     * @return string|null
     */
    public function getSkippingReason(): ?string
    {
        return null;
    }

    /**
     * Determine whether this task should rollback if the given task fails
     *
     * @param AbstractTask|null $task
     * @return bool
     */
    public function shouldRollbackDueTo(?AbstractTask $task): bool
    {
        return true;
    }

    /**
     * Rollback this task
     *
     * @return void
     */
    public function rollback(): void
    {
        if ($this->ranRollback()) {
            return;
        }

        $this->ranRollback = true;
        $this->rolledback = $this->revert() !== false;
    }

    /**
     * Determine whether this task executed the rollback logic
     *
     * @return bool
     */
    public function ranRollback(): bool
    {
        return $this->ranRollback;
    }

    /**
     * Determine whether this task was successfully rolled back
     *
     * @return bool
     */
    public function rolledback(): bool
    {
        return $this->rolledback;
    }

    /**
     * Revert this task
     *
     * @return mixed
     */
    protected function revert()
    {
        return;
    }

    /**
     * Determine whether the successive tasks should be stopped if this task fails
     *
     * @return bool
     */
    public function stopsSuccessiveTasksOnFailure(): bool
    {
        return true;
    }
}
