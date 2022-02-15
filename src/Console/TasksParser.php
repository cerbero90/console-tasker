<?php

namespace Cerbero\ConsoleTasker\Console;

use Cerbero\ConsoleTasker\Tasks;
use Cerbero\ConsoleTasker\Concerns\ConfigAware;
use Illuminate\Support\Str;

/**
 * The tasks parser.
 *
 */
class TasksParser
{
    use ConfigAware;

    /**
     * Parse the tasks contained in the given input
     *
     * @param string $input
     * @return ParsedTasks[]
     */
    public function parse(string $input): array
    {
        $parsedTasks = [];
        $rawTasks = explode(',', $input);

        foreach ($rawTasks as $rawTask) {
            $segments = explode(':', $rawTask, 2);
            $parsedTasks[] = $parsedTask = new ParsedTask();
            $parsedTask->name = $segments[0];
            $parsedTask->parent = $this->guessParent($parsedTask, $segments[1] ?? '');
        }

        return $parsedTasks;
    }

    /**
     * Guess the parent class of the given task
     *
     * @param ParsedTask $parsedTask
     * @param string $modifier
     * @return string
     */
    protected function guessParent(ParsedTask $parsedTask, string $modifier): string
    {
        $modifiers = $this->config('modifiers');

        if (isset($modifiers[$modifier])) {
            return $modifiers[$modifier];
        }

        foreach ($this->config('verbs') as $class => $verbs) {
            if (Str::startsWith(strtolower($parsedTask->name), $verbs)) {
                return $class;
            }
        }

        return Tasks\Task::class;
    }
}
