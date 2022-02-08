<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Input\InputInterface;

/**
 * The I/O aware trait.
 *
 */
trait IOAware
{
    use InteractsWithIO;

    /**
     * Set the console input and output fluently.
     *
     * @param InputInterface $input
     * @param OutputStyle $output
     * @return static
     */
    public function setIO(InputInterface $input, OutputStyle $output): static
    {
        $this->setInput($input);
        $this->setOutput($output);

        return $this;
    }
}
