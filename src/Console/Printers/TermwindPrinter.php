<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\Task;
use Illuminate\Contracts\View\Factory;
use Termwind\Live;

use function Termwind\live;
use function Termwind\terminal;

/**
 * The Termwind printer.
 *
 */
class TermwindPrinter implements Printer
{
    /**
     * The task status labels.
     *
     * @var string[]
     */
    protected static array $statusLabels = [
        Task::STATUS_PENDING => 'IN PROGRESS',
        Task::STATUS_SUCCEEDED => 'COMPLETE',
        Task::STATUS_SKIPPED => 'SKIPPED',
        Task::STATUS_FAILED => 'FAILED',
        Task::STATUS_ROLLEDBACK => 'ROLLED BACK',
        Task::STATUS_FAILED_ROLLBACK => 'FAILED TO ROLLBACK',
    ];

    /**
     * The task status colors.
     *
     * @var string[]
     */
    protected static array $statusColors = [
        Task::STATUS_PENDING => 'text-yellow-500',
        Task::STATUS_SUCCEEDED => 'text-green-500',
        Task::STATUS_SKIPPED => 'text-yellow-500',
        Task::STATUS_FAILED => 'text-red-500',
        Task::STATUS_ROLLEDBACK => 'text-pink-500',
        Task::STATUS_FAILED_ROLLBACK => 'text-red-500',
    ];

    /**
     * The live sections.
     *
     * @var Live[]
     */
    protected array $sections = [];

    /**
     * Instantiate the class.
     *
     * @param Factory $view
     */
    public function __construct(protected Factory $view)
    {
    }

    /**
     * Print the given task while it's running
     *
     * @param Task $task
     * @return void
     */
    public function printRunningTask(Task $task): void
    {
        $hash = spl_object_hash($task);

        $this->sections[$hash] = live(fn () => $this->view->make('console-tasker::task', [
            'task' => $task,
            'color' => static::$statusColors[$task->getStatus()],
            'status' => static::$statusLabels[$task->getStatus()],
            'width' => terminal()->width(),
        ]));
    }

    /**
     * Print the given task after it ran
     *
     * @param Task $task
     * @return void
     */
    public function printRunTask(Task $task): void
    {
        $hash = spl_object_hash($task);

        $this->sections[$hash]->refresh();
    }

    /**
     * Print the given task after it rolled back
     *
     * @param Task $task
     * @return void
     */
    public function printRolledbackTask(Task $task): void
    {
        $hash = spl_object_hash($task);

        $this->sections[$hash]->refresh();
    }

    /**
     * Print the tasks summary
     *
     * @param Summary $summary
     * @return void
     */
    public function printSummary(Summary $summary): void
    {
        //
    }
}
