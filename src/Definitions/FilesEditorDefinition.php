<?php

namespace Cerbero\ConsoleTasker\Definitions;

use Closure;

/**
 * The files editor definition.
 *
 * @mixin \Cerbero\ConsoleTasker\Concerns\ChangesContent
 */
class FilesEditorDefinition extends Definition
{
    /**
     * Set the target to edit
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
