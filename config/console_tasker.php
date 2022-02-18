<?php

use Cerbero\ConsoleTasker\Console\Printers\TermwindPrinter;
use Cerbero\ConsoleTasker\Tasks;

return [
    /*
    |--------------------------------------------------------------------------
    | Console printer
    |--------------------------------------------------------------------------
    |
    | The console printer is responsible for printing the output of tasks that
    | run and potential errors that may occur along the process. Out of the
    | box a default printer is available, but you can set your own below.
    |
    */
    'printer' => TermwindPrinter::class,

    /*
    |--------------------------------------------------------------------------
    | Tasks directory
    |--------------------------------------------------------------------------
    |
    | This setting defines where the tasks should be placed when generated via
    | the included Artisan command. A sensible default is provided but feel
    | free to change it if you need tasks to be in a different directory.
    |
    */
    'tasks_directory' => app_path('Console/Tasks'),

    /*
    |--------------------------------------------------------------------------
    | Task implementation verbs
    |--------------------------------------------------------------------------
    |
    | Common tasks implementations are provided by this package, such as files
    | creators and editors. Based on the verb chosen to name tasks, the apt
    | implementation might be implicitly guessed during tasks generation.
    |
    */
    'verbs' => [
        Tasks\FileCreator::class => ['create', 'generate'],
        Tasks\FilesEditor::class => ['add', 'update'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Task modifiers
    |--------------------------------------------------------------------------
    |
    | Modifiers can be used as a shorthand to generate specific tasks from the
    | Artisan command line. Below you can specify which kind of task should
    | be generated when a modifier is indicated along with the task name.
    |
    */
    'modifiers' => [
        'c' => Tasks\FileCreator::class,
        'e' => Tasks\FilesEditor::class,
    ],
];
