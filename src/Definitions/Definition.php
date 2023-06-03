<?php

namespace Cerbero\ConsoleTasker\Definitions;

use Closure;

/**
 * The abstract definition of a task.
 *
 */
abstract class Definition
{
    /**
     * The summary stub.
     *
     * @var Closure|string
     */
    protected Closure|string $summary;

    /**
     * Whether previous tasks should be rolled back if this task fails.
     *
     * @var Closure|bool
     */
    protected Closure|bool $dontRollbackPreviousTasks = false;

    /**
     * Whether next tasks should be stopped on failure.
     *
     * @var Closure|bool
     */
    protected Closure|bool $dontStopNextTasks = false;

    /**
     * The reason why the task succeeded.
     *
     * @var Closure|string
     */
    protected Closure|string $succeedBecause;

    /**
     * The reason why the task was skipped.
     *
     * @var Closure|string
     */
    protected Closure|string $skipBecause;

    /**
     * The reason why the task failed.
     *
     * @var Closure|string
     */
    protected Closure|string $failBecause;

    /**
     * The reason why the rollback of the task succeeded.
     *
     * @var Closure|string
     */
    protected Closure|string $succeedRollbackBecause;

    /**
     * The reason why the rollback of the task failed.
     *
     * @var Closure|string
     */
    protected Closure|string $failRollbackBecause;

    /**
     * The condition to skip the task.
     *
     * @var Closure|bool
     */
    protected Closure|bool $skipIf;

    /**
     * The changes that need to be applied manually.
     *
     * @var Closure|string
     */
    protected Closure|string $manually;

    /**
     * Instantiate the class.
     *
     * @param string $purpose
     */
    public function __construct(protected string $purpose)
    {
    }

    /**
     * Statically instantiate the task definition
     *
     * @param string $purpose
     * @return static
     */
    public static function to(string $purpose): static
    {
        return new static($purpose);
    }

    /**
     * Define the summary stub
     *
     * @param Closure|string $summary
     * @return static
     */
    public function summary(Closure|string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Do not rollback previous tasks if this task fails
     *
     * @param Closure|null $callback
     * @return static
     */
    public function dontRollbackPreviousTasks(Closure $callback = null): static
    {
        $this->dontRollbackPreviousTasks = $callback ?: true;

        return $this;
    }

    /**
     * Do not stop next tasks if this task fails
     *
     * @param Closure|null $callback
     * @return static
     */
    public function dontStopNextTasks(Closure $callback = null): static
    {
        $this->dontStopNextTasks = $callback ?: true;

        return $this;
    }

    /**
     * Define the reason why the task succeeded
     *
     * @param Closure|string $reason
     * @return static
     */
    public function succeedBecause(Closure|string $reason): static
    {
        $this->succeedBecause = $reason;

        return $this;
    }

    /**
     * Define the reason why the task was skipped
     *
     * @param Closure|string $reason
     * @return static
     */
    public function skipBecause(Closure|string $reason): static
    {
        $this->skipBecause = $reason;

        return $this;
    }

    /**
     * Define the reason why the task failed
     *
     * @param Closure|string $reason
     * @return static
     */
    public function failBecause(Closure|string $reason): static
    {
        $this->failBecause = $reason;

        return $this;
    }

    /**
     * Define the reason why the rollback of the task succeeded
     *
     * @param Closure|string $reason
     * @return static
     */
    public function succeedRollbackBecause(Closure|string $reason): static
    {
        $this->succeedRollbackBecause = $reason;

        return $this;
    }

    /**
     * Define the reason why the rollback of the task failed
     *
     * @param Closure|string $reason
     * @return static
     */
    public function failRollbackBecause(Closure|string $reason): static
    {
        $this->failRollbackBecause = $reason;

        return $this;
    }

    /**
     * Skip the task if the given condition is true
     *
     * @param Closure|bool $condition
     * @return static
     */
    public function skipIf(Closure|bool $condition): static
    {
        $this->skipIf = $condition;

        return $this;
    }

    /**
     * Define the changes that needs to be applied manually
     *
     * @param Closure|string $change
     * @return static
     */
    public function manually(Closure|string $change): static
    {
        $this->manually = $change;

        return $this;
    }

    /**
     * Dynamically retrieve data
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->$name ?? null;
    }
}
