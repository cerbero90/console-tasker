---
title: Generating console tasker
description: How to generate a Console Tasker via Artisan.
position: 100
category: The basics
implementations:
  - 'generic task: the default implementation, it can execute any logic'
  - 'file creator: generates a new file, great for automatic code generation'
  - 'files editor: updates existing files, also great for code auto-generation'
---

By **console tasker** we mean an Artisan command running one or more tasks. This package provides a command to generate console taskers and related tasks.


## Generating the command

To generate only the Artisan command, without any task, we can run:

```bash
php artisan make:console-tasker MyCommand
```

that will produce the following output:

<img src="make_console_tasker.png">

As we can note, the command was successfully created while the generatation of tasks was skipped, since we didn't specify any. The output also shows the command that was created and reminds us to update it with signature, description and a list of tasks to run.

Now the newly generated command looks like this:

```php
namespace App\Console\Commands;

use App\Console\Tasks\MyCommand as Tasks;
use Cerbero\ConsoleTasker\Concerns\RunsTasks;
use Illuminate\Console\Command;

class MyCommand extends Command
{
    use RunsTasks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The tasks to run.
     *
     * @var string[]
     */
    protected array $tasks = [
        // @todo list tasks to run
    ];
}
```

We can also define the signature of a command when we generate it:

```bash
php artisan make:console-tasker MyCommand --command=my-command

php artisan make:console-tasker MyCommand -c my-command
```

By doing so, the signature of the command is set to `my-command` and the reminder changes to <badge>define command description and list tasks to run</badge>.


## Generating the tasks

Generating tasks is possible by simply providing the the `--tasks` (or `-t`) option:

```bash
php artisan make:console-tasker MyCommand --tasks=FirstTask,SecondTask

php artisan make:console-tasker MyCommand -t FirstTask,SecondTask
```

As shown above, multiple tasks can be specified by separating them with a comma.

The output now shows also the generated tasks and suggests us to implement them:

<img src="make_console_tasker_tasks.png">

<alert>

Tasks are generated in `app/Console/Tasks` by default, but we can also [choose a different directory](installation#tasks_directory).

</alert>

Out of the box Console Tasker provides 3 different task implementations:

<list :items="implementations"></list>

If we want to generate a file creator or a files editor, we can append modifiers when defining the tasks to generate:

```bash
php artisan make:console-tasker MyCommand -t CreateController:c,AddRelationToUser:e
```

Modifiers are appended to task names with a column. By default a file creator can be generated with the modifier <badge>c</badge> and a files editor with the modifier <badge>e</badge>

<alert>

[Custom modifiers and implementations](installation#modifiers) can be added to the configuration of Console Tasker.

</alert>

A variant to modifiers is using common verbs to 

This is how the 3 task implementations look like:

<code-group>
  <code-block label="generic task" active>

```php
namespace App\Console\Tasks\MyCommand;

use Cerbero\ConsoleTasker\Tasks\Task;

/**
 * The task to notify admin.
 *
 */
class NotifyAdmin extends Task
{
    /**
     * Run the task
     *
     * @return mixed
     */
    protected function run()
    {
        //
    }
}
```

  </code-block>
  <code-block label="file creator">

```php
namespace App\Console\Tasks\MyCommand;

use Cerbero\ConsoleTasker\Tasks\FileCreator;

/**
 * The task to create controller.
 *
 */
class CreateController extends FileCreator
{
    /**
     * Retrieve the fully qualified name of the file to create
     *
     * @return string|null
     */
    protected function getFullyQualifiedName(): ?string
    {
        return null;
    }
}
```

  </code-block>
  <code-block label="files editor">

```php
namespace App\Console\Tasks\MyCommand;

use Cerbero\ConsoleTasker\Tasks\FilesEditor;

/**
 * The task to add relation to user.
 *
 */
class AddRelationToUser extends FilesEditor
{
    /**
     * Run the task
     *
     * @return mixed
     */
    protected function run()
    {
        $this->file('path/to/file/to/update')
            ->addLineAfterLast('text to search', 'line to add')
            ->needsManualUpdateTo('implement something');
    }
}
```

  </code-block>
</code-group>

+ **verbs + config link** //////////////
+ **asterisk in task names to generate stubs** //////////////

## Customizing the stubs
