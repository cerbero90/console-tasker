<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Tasks\Task;

/**
 * The rollback scope.
 *
 */
class RollbackScope
{
    /**
     * Instantiate the class.
     *
     * @param Task $rolledbackTask
     * @param Task $failedTask
     */
    public function __construct(public Task $rolledbackTask, public Task $failedTask)
    {
    }
}
