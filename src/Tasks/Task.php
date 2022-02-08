<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Concerns\DataAware;
use Cerbero\ConsoleTasker\Concerns\IOAware;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Throwable;

/**
 * The abstract task.
 *
 */
abstract class Task
{
    use DataAware;
    use IOAware;

    /**
     * The task purpose.
     *
     * @var string
     */
    protected string $purpose;

    /**
     * Whether the task ran.
     *
     * @var bool
     */
    protected bool $ran = false;

    /**
     * Whether the task succeeded.
     *
     * @var bool
     */
    protected bool $succeeded = false;

    /**
     * Whether the task was skipped.
     *
     * @var bool
     */
    protected bool $wasSkipped = false;

    /**
     * The reason why this task should not run.
     *
     * @var string|null
     */
    protected ?string $skippingReason = null;

    /**
     * The error that caused this task to fail.
     *
     * @var string|null
     */
    protected ?string $error = null;

    /**
     * The exception that caused this task to fail.
     *
     * @var Throwable|null
     */
    protected ?Throwable $exception = null;

    /**
     * The exception that caused this task rollback to fail.
     *
     * @var Throwable|null
     */
    protected ?Throwable $rollbackException = null;

    /**
     * Whether this task executed the rollback logic.
     *
     * @var bool
     */
    protected bool $ranRollback = false;

    /**
     * Whether this task was successfully rolled back.
     *
     * @var bool
     */
    protected bool $rolledback = false;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Run the task
     *
     * @return mixed
     */
    abstract protected function run();

    /**
     * Perform the task
     *
     * @return bool
     */
    public function perform(): bool
    {
        if ($this->ran()) {
            return $this->succeeded();
        }

        if (!$this->shouldRun()) {
            $this->wasSkipped = true;
            return false;
        }

        $this->ran = true;

        try {
            $this->succeeded = $this->run() !== false;
        } catch (Throwable $e) {
            $this->succeeded = false;
            $this->setException($e);
        }

        return $this->succeeded();
    }

    /**
     * Set the application
     *
     * @param Application $app
     * @return static
     */
    public function setApp(Application $app): static
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Retrieve this task purpose
     *
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose ??= (string) Str::of(static::class)->classBasename()->snake(' ');
    }

    /**
     * Determine whether this task ran
     *
     * @return bool
     */
    public function ran(): bool
    {
        return $this->ran;
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
     * Determine whether this task was skipped
     *
     * @return bool
     */
    public function wasSkipped(): bool
    {
        return $this->wasSkipped;
    }

    /**
     * Determine whether this task failed
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->ran() && !$this->succeeded() && !$this->wasSkipped();
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
     * @return static
     */
    public function setException(Throwable $exception): static
    {
        $this->exception = $exception;
        $this->error ??= $exception->getMessage();

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
        return $this->skippingReason;
    }

    /**
     * Determine whether this task should rollback if the given task fails
     *
     * @param Task $task
     * @return bool
     */
    public function shouldRollbackDueTo(Task $task): bool
    {
        return true;
    }

    /**
     * Revert this task
     *
     * @return void
     */
    public function revert(): void
    {
        if ($this->ranRollback()) {
            return;
        }

        $this->ranRollback = true;

        try {
            $this->rolledback = $this->rollback() !== false;
        } catch (Throwable $e) {
            $this->rollbackException = $e;
        }
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
     * Rollback this task
     *
     * @return mixed
     */
    protected function rollback()
    {
        return;
    }

    /**
     * Determine whether the next tasks should be stopped if this task fails
     *
     * @return bool
     */
    public function stopsOnFailure(): bool
    {
        return true;
    }
}
