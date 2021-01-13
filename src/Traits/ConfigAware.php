<?php

namespace Cerbero\ConsoleTasker\Traits;

use Illuminate\Container\Container;

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
        /** @var \Illuminate\Contracts\Config\Repository */
        $config = Container::getInstance()->make('config');

        return $config->get("console_tasker.$key", $default);
    }
}
