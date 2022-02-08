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
    protected $parentClassMap = [
        'c' => Tasks\FileCreator::class,
        'u' => Tasks\FilesEditor::class,
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
            $parsedTasks[] = $parsedTask = new ParsedTask();
            $parsedTask->name = explode(':', $rawTask)[0];
            $parsedTask->parentClass = $this->guessParentClass($rawTask);
        }

        return $parsedTasks;
    }

    /**
     * Retrieve the guessed parent task class
     *
     * @param string $rawTask
     * @return string
     */
    protected function guessParentClass(string $rawTask): string
    {
        $segments = explode(':', $rawTask);

        if ($key = $segments[1] ?? null) {
            return $this->parentClassMap[$key] ?? Tasks\Task::class;
        }

        foreach ($this->config('verbs') as $class => $verbs) {
            if (Str::startsWith(strtolower($segments[0]), $verbs)) {
                return $class;
            }
        }

        return Tasks\Task::class;
    }
}
