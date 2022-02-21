<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Illuminate\Support\Str;
use Throwable;

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
     * @param Throwable|null $exception
     */
    public function __construct(protected string $class, protected ?Throwable $exception = null)
    {
        $this->failureReason = $exception?->getMessage();
    }

    /**
     * Run the task
     *
     * @return mixed
     */
    protected function run()
    {
        return false;
    }

    /**
     * Retrieve the invalid task class
     *
     * @param string $class
     * @return string
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
        return (string) Str::of($this->class)->classBasename()->snake(' ');
    }

    /**
     * Retrieve the error that caused this task to fail
     *
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason ?: "The item [{$this->class}] is not a valid task";
    }
}
