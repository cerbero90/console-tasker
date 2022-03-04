---
title: Introduction
description: 'Laravel package to run and manage tasks in Artisan commands.'
position: 1
category: Getting Started
fullscreen: true
features:
  - Create lightweight and consistent Artisan commands
  - Validate console input with Laravel validation rules
  - Customize everything by publishing and editing stubs, views, etc.
  - Run dynamic workflows by adding or skipping tasks programmatically
  - Control the rollback of previous tasks when a task fails
  - Print beautiful and fully customizable output on the console
  - Implement idempotent tasks to avoid duplications and repetitions
  - Handle and render exceptions when tasks fail
  - Generate multiple tasks easily via Artisan
  - Auto-generate or update code with a fluent and simple syntax
  - Get reminded to manually modify the auto-generated code when needed
---

Console Tasker is a Laravel package to run and manage tasks in Artisan commands.

Tasks can execute any logic and run sequentially. Each task determines whether it should be run, how to print its outcome to the console, whether to rollback previous tasks when it fails and more.

The goal of this package is to create lean, powerful, idempotent and beautiful Artisan commands with zero effort.

It also provides ready-to-use tasks to create Artisan commands that auto-generate code for your Laravel apps.


## Features

<list :items="features"></list>
