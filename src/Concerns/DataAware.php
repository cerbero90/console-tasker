<?php

namespace Cerbero\ConsoleTasker\Concerns;

use Cerbero\ConsoleTasker\Data;

/**
 * The data-aware trait.
 *
 */
trait DataAware
{
    /**
     * The data for tasks and stubs.
     *
     * @var Data
     */
    protected Data $data;

    /**
     * Set the data for tasks and stubs
     *
     * @param Data $data
     * @return static
     */
    public function setData(Data $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Retrieve the data for tasks and stubs
     *
     * @return Data
     */
    public function getData(): Data
    {
        return $this->data ??= new Data();
    }
}
