<?php

namespace Cerbero\ConsoleTasker\Definitions;

use Closure;

/**
 * The file creator definition.
 *
 */
class FileCreatorDefinition extends Definition
{
    /**
     * Set the target to create
     *
     * @param Closure|string $target
     * @return static
     */
    public function target(Closure|string $target): static
    {
        $this->target = $target;

        return $this;
    }
}
