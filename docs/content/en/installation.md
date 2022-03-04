---
title: Installation
description: 'How to install and configure Console Tasker.'
position: 2
category: Getting Started
---

Console Tasker is very easy to install and configure.

## Installation

Require the package with Composer:

```bash
composer require cerbero/console-tasker
```

## Configuration

This step is **optional** but allows us to customize Console Tasker depending on our needs.

Run this command to publish the package configuration to `config/console-tasker`:

```bash
php artisan vendor:publish --tag=console-tasker
```

Let's have a look at the settings one-by-one:


### `tasks_directory`

Here is the directory where all tasks should be put in. By default tasks can be implemented or auto-generated in `app/Console/Tasks` but we can change it as we need.


### `printer`

This setting allows us to set a custom printer, the responsible for showing tasks outcome in the console.

The default printer uses [Termwind](https://github.com/nunomaduro/termwind), a great tool to render HTML and [Tailwind CSS](https://tailwindcss.com) utility classes in your terminal. ✨

<alert>

To build and customize your own printer, have a look at [this section](building-printer).

</alert>

### `modifiers`

Modifiers provide a quick way to specify the implementation of a task generated via Artisan. There are 2 modifiers available by default:

- <badge>c</badge> the generated task extends `Cerbero\ConsoleTasker\Tasks\FileCreator` which makes it easy to auto-generate files in your app.

- <badge>e</badge> the generated task extends `Cerbero\ConsoleTasker\Tasks\FilesEditor` which makes it easy to auto-generate code in your existing files.

More modifiers can be added or replaced with our own task implementations, speeding up the generation of the tasks that we use the most.

<alert>

If this sounds confusing at the moment - and it probably does 🤔 - have a look at [this section](generating-tasks) for more info.

</alert>


### `verbs`

If you are a lazy dev - like me 🐼 - and find it tedious or don't like to add modifiers when generating tasks, you can associate the verbs you use to name tasks to a given task implementation.

By default when we generate a task which name starts with `create` or `generate`, a task extending `Cerbero\ConsoleTasker\Tasks\FileCreator` is generated.

Likewise, when we generate a task which name starts with `add` or `update`, a task extending `Cerbero\ConsoleTasker\Tasks\FilesEditor` is generated.

Feel free to change verbs and add your own task implementations as you need!

<alert>

Again, all this will hopefully make more sense after reading [this section](generating-tasks). 🙂

</alert>
