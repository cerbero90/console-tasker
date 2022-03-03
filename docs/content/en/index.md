---
title: Introduction
description: 'Laravel package to run and manage tasks in Artisan commands.'
position: 1
category: ''
fullscreen: true
advantages:
  - Lightweight and consistent Artisan commands
  - Seamless input validation using Laravel rules
  - '100% customizable: publish and edit stubs, views, etc.'
  - 'Dynamic workflow: add or skip tasks programmatically'
  - Conditional rollback on tasks failure
  - Beautiful and fully customizable console output
  - Easy implementation of idempotent tasks
  - Handling and rendering of exceptions
  - Generation of tasks via Artisan command
  - Fluent syntax to auto-generate source code or update it
  - Reminders for manually change generated code when need be
---

Console Tasker is a Laravel package to run and manage tasks in Artisan commands.

Tasks execute any logic and run sequentially. Each task can determine whether it should run, rollback previous tasks on failure, print its outcome on the console and more.

The goal of this package is to create lean, powerful, idempotent and beautiful Artisan commands with no effort.


## Advantages

<list :items="advantages"></list>
