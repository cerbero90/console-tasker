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
     * The parent class map.
     *
     * @var array
     */
    protected array $parentMap = [
        'c' => Tasks\FileCreator::class,
        'e' => Tasks\FilesEditor::class,
    ];

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
            $this->handleModifiers($parsedTask, $segments[1] ?? '');
        }

        return $parsedTasks;
    }

    /**
     * Handle the given modifiers
     *
     * @param ParsedTask $parsedTask
     * @param string $modifiers
     * @return void
     */
    protected function handleModifiers(ParsedTask $parsedTask, string $modifiers): void
    {
        $modifiers = str_replace('s', '', $modifiers, $count);

        $parsedTask->needsStub = $count > 0;

        $parsedTask->parent = $this->guessParent($parsedTask, $modifiers);
    }

    /**
     * Guess the parent class of the given task
     *
     * @param ParsedTask $parsedTask
     * @param string $modifiers
     * @return string
     */
    protected function guessParent(ParsedTask $parsedTask, string $modifiers): string
    {
        if ($modifiers) {
            return $this->parentMap[$modifiers] ?? Tasks\Task::class;
        }

        foreach ($this->config('verbs') as $class => $verbs) {
            if (Str::startsWith(strtolower($parsedTask->name), $verbs)) {
                return $class;
            }
        }

        return Tasks\Task::class;
    }
}
