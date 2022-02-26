<?php

namespace Cerbero\ConsoleTasker\Console\Printers;

use Closure;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Termwind\HtmlRenderer;
use Termwind\Termwind;

/**
 * The live section: polyfill for the feat/live branch.
 *
 */
class LiveSection
{
    /**
     * The live section.
     *
     * @var ConsoleSectionOutput
     */
    protected ConsoleSectionOutput $section;

    /**
     * Instantiate the class.
     *
     * @param Closure $callback
     */
    public function __construct(protected Closure $callback)
    {
        /** @var ConsoleOutputInterface $renderer */
        if (!is_subclass_of($renderer = Termwind::getRenderer(), ConsoleOutputInterface::class)) {
            throw new RuntimeException('Unable to render live sections');
        }

        $this->section = $renderer->section();
    }

    /**
     * Statically instantiate and render the live section
     *
     * @param Closure $callback
     * @return static
     */
    public static function renderWith(Closure $callback): static
    {
        $section = new static($callback);

        return $section->render();
    }

    /**
     * Render the live section
     *
     * @return static
     */
    public function render(): static
    {
        $html = (new HtmlRenderer())->parse((string) call_user_func($this->callback));

        $this->section->write($html->toString());

        return $this;
    }

    /**
     * Refresh the live section
     *
     * @return static
     */
    public function refresh(): static
    {
        $this->section->clear();

        return $this->render();
    }
}
