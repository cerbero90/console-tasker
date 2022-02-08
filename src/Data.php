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
     * Turn the data into replacements for stubs
     *
     * @return array
     */
    public function toReplacements(): array
    {
        $replacements = [];

        foreach ($this->attributes as $key => $value) {
            $replacements["{{ $key }}"] = $value;
        }

        return $replacements;
    }
}
