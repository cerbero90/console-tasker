---
title: Introduction
description: Laravel package to run and manage tasks in Artisan commands.
position: 1
category: Getting started
fullscreen: true
features:
  - Create lightweight and consistent Artisan commands
  - Validate console input with Laravel validation rules
  - Customize everything by publishing and editing stubs, views, etc.
  - Run dynamic workflows by adding or skipping tasks programmatically
  - Control the rollback of previous tasks when a task fails
  - Print beautiful and fully customizable output on the console
  - Achieve idempotent tasks and get the same outcome every time they run
  - Handle and render exceptions when tasks fail
  - Generate multiple tasks easily via Artisan
  - Auto-generate or update code with a fluent and simple syntax
  - Get reminded to manually modify the auto-generated code when needed
---

Console Tasker is a Laravel package to create lean, powerful, idempotent and beautiful Artisan commands by running tasks.

Tasks can execute any logic and run sequentially. Each task determines whether it should be run, how to print its outcome to the console, whether to rollback previous tasks when it fails and more.

Console Tasker also provides ready-to-use tasks to create Artisan commands that generate code for your Laravel apps.


## Features

<list :items="features"></list>
