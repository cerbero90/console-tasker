<?php

namespace Cerbero\ConsoleTasker\Providers;

use Cerbero\ConsoleTasker\Concerns\ConfigAware;
use Cerbero\ConsoleTasker\Console\Commands\MakeConsoleTaskerCommand;
use Cerbero\ConsoleTasker\Console\Printers\BasicPrinter;
use Cerbero\ConsoleTasker\Console\Printers\Printer;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * The console tasker service provider.
 *
 */
class ConsoleTaskerServiceProvider extends ServiceProvider
{
    use ConfigAware;

    /**
     * The configuration file path.
     *
     * @var string
     */
    protected const CONFIG = __DIR__ . '/../../config/console_tasker.php';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(MakeConsoleTaskerCommand::class);
        }

        $this->publishes([
            static::CONFIG => $this->app->configPath('console_tasker.php'),
        ], 'console-tasker');

        $this->publishes([
            __DIR__ . '/../Console/Tasks/stubs' => $this->app->basePath('stubs/console-tasker'),
        ], 'console-tasker-stubs');
    }

    /**
     * Register the bindings
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(static::CONFIG, 'console_tasker');

        $this->app->bind(BasicPrinter::class, function () {
            return new BasicPrinter(new OutputStyle(new ArgvInput(), new ConsoleOutput()));
        });

        $this->app->bind(Printer::class, $this->config('printer'));
    }
}
