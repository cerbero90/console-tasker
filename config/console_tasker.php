<?php

use Cerbero\ConsoleTasker\Console\Printers\DefaultPrinter;
use Cerbero\ConsoleTasker\Tasks;

return [
    /*
    |--------------------------------------------------------------------------
    | Console printer
    |--------------------------------------------------------------------------
    |
    | The console printer is responsible for printing the output of tasks that
    | run and potential errors that may occur along the process. Out of the
    | box a default printer is available, but you can set your own below 
    |
    */
    'printer' => DefaultPrinter::class,

    /*
    |--------------------------------------------------------------------------
    | Tasks directory
    |--------------------------------------------------------------------------
    |
    | This setting defines where the tasks should be placed when generated via
    | the included Artisan command. A sensible default is provided but feel
    | free to change it if you need tasks to be in a different directory
    |
    */
    'tasks_directory' => 'Console/Tasks',

    /*
    |--------------------------------------------------------------------------
    | Task implementation verbs
    |--------------------------------------------------------------------------
    |
    | Common tasks implementations are provided by this package, such as files
    | creators and updaters. Depending on verbs used to name tasks, the apt
    | implementation might be implicitly guessed during tasks generation
    |
    */
    'verbs' => [
        Tasks\AbstractCreatorTask::class => ['create', 'generate'],
        Tasks\AbstractFilesManipulatorTask::class => ['add', 'update'],
    ]
];

// add documentation to readme