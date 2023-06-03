<?php

namespace Cerbero\ConsoleTasker\Tasks;

use Cerbero\ConsoleTasker\Definitions\FileCreatorDefinition;

/**
 * The anonymous file creator.
 *
 */
class AnonymousFileCreator extends FileCreator
{
    /**
     * Instantiate the class.
     *
     * @param FileCreatorDefinition $definition
     */
    public function __construct(protected FileCreatorDefinition $definition)
    {
    }
}
