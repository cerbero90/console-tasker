<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Definitions\TaskDefiner;

/**
 * Define a new task to run
 *
 * @param string $purpose
 * @return TaskDefiner
 */
function task(string $purpose): TaskDefiner
{
    return new TaskDefiner($purpose);
}
