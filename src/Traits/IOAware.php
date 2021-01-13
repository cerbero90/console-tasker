<?php

namespace Cerbero\ConsoleTasker\Traits;

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
     * Set console input and output.
     *
     * @param InputInterface $input
     * @param OutputStyle $output
     * @return self
     */
    public function setIO(InputInterface $input, OutputStyle $output): self
    {
        $this->setInput($input);
        $this->setOutput($output);

        return $this;
    }
}
