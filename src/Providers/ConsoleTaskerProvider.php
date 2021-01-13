<?php

namespace Cerbero\ConsoleTasker\Providers;

use Cerbero\ConsoleTasker\Console\Commands\MakeConsoleTaskerCommand;
use Cerbero\ConsoleTasker\Console\Printers\DefaultPrinter;
use Cerbero\ConsoleTasker\Console\Printers\PrinterInterface;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * The console tasker provider.
 *
 */
class ConsoleTaskerProvider extends ServiceProvider
{
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
            static::CONFIG => $this->app->configPath('console_tasker.php')
        ], 'console_tasker');
    }

    /**
     * Register the bindings
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(static::CONFIG, 'console_tasker');

        $this->app->bind(DefaultPrinter::class, function () {
            return new DefaultPrinter(new OutputStyle(new ArgvInput(), new ConsoleOutput()));
        });

        $this->app->bind(PrinterInterface::class, $this->app->config['console_tasker.printer']);
    }
}
