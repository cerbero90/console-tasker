<?php

namespace Cerbero\ConsoleTasker;

use Illuminate\Support\Fluent;

/**
 * The data for tasks and stubs.
 *
 */
class Data extends Fluent
{
    /**
     * Add the given data if it's not already present
     *
     * @param array $data
     * @return static
     */
    public function add(array $data): static
    {
        return new static($this->attributes + $data);
    }

    /**
     * Merge the given data
     *
     * @param array $data
     * @return static
     */
    public function merge(array $data): static
    {
        $attributes = array_merge($this->attributes, $data);

        return new static($attributes);
    }

    /**
     * Retrieve the parsed replacements for stubs
     *
     * @return array
     */
    public function parseReplacements(): array
    {
        $search = $replace = [];

        foreach ($this->attributes as $key => $value) {
            $search[] = '{{ ' . trim($key, '{ }') . ' }}';
            $replace[] = $value;
        }

        return [$search, $replace];
    }
}
