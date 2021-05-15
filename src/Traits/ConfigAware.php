<?php

namespace Cerbero\ConsoleTasker\Traits;

use Illuminate\Support\Facades\Config;

/**
 * The config aware trait.
 *
 */
trait ConfigAware
{
    /**
     * Retrieve the given configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config(string $key, $default = null)
    {
        return Config::get("console_tasker.$key", $default);
    }
}
