<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Tasks\AbstractTask;

/**
 * The rollback scope.
 *
 */
class RollbackScope
{
    /**
     * The rolledback task.
     *
     * @var AbstractTask
     */
    public $rolledbackTask;

    /**
     * The failed task.
     *
     * @var AbstractTask
     */
    public $failedTask;

    /**
     * Instantiate the class.
     *
     * @param AbstractTask $rolledbackTask
     * @param AbstractTask $failedTask
     */
    public function __construct(AbstractTask $rolledbackTask, AbstractTask $failedTask)
    {
        $this->rolledbackTask = $rolledbackTask;
        $this->failedTask = $failedTask;
    }
}
