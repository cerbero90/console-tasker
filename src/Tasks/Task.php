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

    public const STATUS_PENDING = 0;
    public const STATUS_SUCCEEDED = 1;
    public const STATUS_SKIPPED = 2;
    public const STATUS_FAILED = 3;
    public const STATUS_ROLLEDBACK = 4;
    public const STATUS_FAILED_ROLLBACK = 5;

    /**
     * Whether the task needs a stub to work.
     *
     * @var bool
     */
    protected static bool $needsStub = false;

    /**
     * The stub to print when displaying the summary.
     *
     * @var string|null
     */
    protected static ?string $summaryStub = null;

    /**
     * The data to fill the summary stub with.
     *
     * @var array
     */
    protected static array $summaryData = [];

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

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
     * Whether previous tasks should be rolled back if this task fails.
     *
     * @var bool
     */
    protected bool $rollbacksOnFailure = true;

    /**
     * Whether next tasks should be stopped if this task fails.
     *
     * @var bool
     */
    protected bool $stopsOnFailure = true;

    /**
     * The reason why this task succeeded.
     *
     * @var string|null
     */
    protected ?string $successReason = null;

    /**
     * The reason why this task should not be run.
     *
     * @var string|null
     */
    protected ?string $skippingReason = null;

    /**
     * The reason why this task failed.
     *
     * @var string|null
     */
    protected ?string $failureReason = null;

    /**
     * The reason why the rollback of this task succeeded.
     *
     * @var string|null
     */
    protected ?string $rollbackSuccessReason = null;

    /**
     * The reason why the rollback of this task failed.
     *
     * @var string|null
     */
    protected ?string $rollbackFailureReason = null;

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
     * Run the task
     *
     * @return mixed
     */
    abstract protected function run();

    /**
     * Determine whether the task needs a stub to work
     *
     * @return bool
     */
    public static function needsStub(): bool
    {
        return static::$needsStub;
    }

    /**
     * Retrieve the stub to print when displaying the summary.
     *
     * @return string|null
     */
    public static function getSummaryStub(): ?string
    {
        return static::$summaryStub;
    }

    /**
     * Retrieve the data to fill the summary stub with
     *
     * @return array
     */
    public static function getSummaryData(): array
    {
        return static::$summaryData;
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
            $this->exception = $e;
            $this->failureReason ??= $e->getMessage();
        }

        return $this->succeeded();
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
     * Determine whether this task should run
     *
     * @return bool
     */
    public function shouldRun(): bool
    {
        return true;
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
     * Determine whether this task is invalid
     *
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this instanceof InvalidTask;
    }

    /**
     * Determine whether this task failed to rollback
     *
     * @return bool
     */
    public function failedRollback(): bool
    {
        return $this->ranRollback() && !$this->rolledback();
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
            $this->rollbackFailureReason ??= $e->getMessage();
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
        return $this->failRollbackWithReason('The rollback logic was not implemented');
    }

    /**
     * Determine whether previous tasks should be rolled back if this task fails
     *
     * @return bool
     */
    public function rollbacksOnFailure(): bool
    {
        return $this->rollbacksOnFailure;
    }

    /**
     * Determine whether next tasks should be stopped if this task fails
     *
     * @return bool
     */
    public function stopsOnFailure(): bool
    {
        return $this->stopsOnFailure;
    }

    /**
     * Succeed the task while setting the given reason
     *
     * @param string $reason
     * @return bool
     */
    protected function succeedWithReason(string $reason): bool
    {
        $this->successReason = $reason;

        return true;
    }

    /**
     * Skip the task while setting the given reason
     *
     * @param string $reason
     * @return bool
     */
    protected function skipWithReason(string $reason): bool
    {
        $this->failureReason = $reason;

        return false;
    }

    /**
     * Fail the task while setting the given reason
     *
     * @param string $reason
     * @return bool
     */
    protected function failWithReason(string $reason): bool
    {
        $this->failureReason = $reason;

        return false;
    }

    /**
     * Succeed the task rollback while setting the given reason
     *
     * @param string $reason
     * @return bool
     */
    protected function succeedRollbackWithReason(string $reason): bool
    {
        $this->rollbackSuccessReason = $reason;

        return true;
    }

    /**
     * Fail the task rollback while setting the given reason
     *
     * @param string $reason
     * @return bool
     */
    protected function failRollbackWithReason(string $reason): bool
    {
        $this->rollbackFailureReason = $reason;

        return false;
    }

    /**
     * Retrieve this task status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return match (true) {
            $this->wasSkipped() => static::STATUS_SKIPPED,
            !$this->ran() => static::STATUS_PENDING,
            $this->failedRollback() => static::STATUS_FAILED_ROLLBACK,
            $this->rolledback() => static::STATUS_ROLLEDBACK,
            $this->failed() => static::STATUS_FAILED,
            $this->succeeded() => static::STATUS_SUCCEEDED,
        };
    }

    /**
     * Retrieve the reason of the task status, if any
     *
     * @return string|null
     */
    public function getStatusReason(): ?string
    {
        return match ($this->getStatus()) {
            static::STATUS_SUCCEEDED => $this->getSuccessReason(),
            static::STATUS_SKIPPED => $this->getSkippingReason(),
            static::STATUS_FAILED => $this->getFailureReason(),
            static::STATUS_ROLLEDBACK => $this->getRollbackSuccessReason(),
            static::STATUS_FAILED_ROLLBACK => $this->getRollbackFailureReason(),
            default => null,
        };
    }

    /**
     * Retrieve the reason why this task succeeded
     *
     * @return string|null
     */
    public function getSuccessReason(): ?string
    {
        return $this->successReason;
    }

    /**
     * Retrieve the reason why this task was not run
     *
     * @return string|null
     */
    public function getSkippingReason(): ?string
    {
        return $this->skippingReason;
    }

    /**
     * Retrieve the reason why this task failed
     *
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason;
    }

    /**
     * Retrieve the reason why the rollback of this task succeeded
     *
     * @return string|null
     */
    public function getRollbackSuccessReason(): ?string
    {
        return $this->rollbackSuccessReason;
    }

    /**
     * Retrieve the reason why the rollback of this task failed
     *
     * @return string|null
     */
    public function getRollbackFailureReason(): ?string
    {
        return $this->rollbackFailureReason;
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
     * Retrieve the exception that caused this task rollback to fail
     *
     * @return Throwable|null
     */
    public function getRollbackException(): ?Throwable
    {
        return $this->rollbackException;
    }

    /**
     * Dynamically retrieve data
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->getData()->get($name);
    }
}
