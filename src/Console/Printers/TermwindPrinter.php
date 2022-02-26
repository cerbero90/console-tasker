<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Cerbero\ConsoleTasker\Summary;
use Cerbero\ConsoleTasker\Tasks\Task;
use Illuminate\Contracts\View\Factory;
use NunoMaduro\Collision\Adapters\Laravel\Inspector;
use NunoMaduro\Collision\Writer;

use function Termwind\render;
use function Termwind\terminal;

/**
 * The Termwind printer.
 *
 */
class TermwindPrinter extends Printer
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
     * @var LiveSection[]
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
    public function printExecutingTask(Task $task): void
    {
        $hash = spl_object_hash($task);

        $this->sections[$hash] = LiveSection::renderWith(fn () => $this->view->make('console-tasker::task', [
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
    public function printExecutedTask(Task $task): void
    {
        $this->refreshTask($task);
    }

    /**
     * Refresh the live section of the given task
     *
     * @param Task $task
     * @return void
     */
    protected function refreshTask(Task $task): void
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
        $this->refreshTask($task);
    }

    /**
     * Print the given summary stub
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    protected function printSummaryStub(string $path, array $data): void
    {
        $this->render($path, $data);
    }

    /**
     * Render the given view with the provided data
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    protected function render(string $view, array $data): void
    {
        $view = $this->view->make($view, $data);

        render($view->render());
    }

    /**
     * Print the tasks error
     *
     * @param Summary $summary
     * @return void
     */
    protected function printErrors(Summary $summary): void
    {
        $this->render('console-tasker::error', [
            'exception' => $exception = $summary->getFirstException(),
        ]);

        if ($exception) {
            (new Writer())->write(new Inspector($exception));

            render('<div class="mb-0"></div>');
        }
    }
}
