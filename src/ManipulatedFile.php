<?php

namespace Cerbero\ConsoleTasker;

use Cerbero\ConsoleTasker\Concerns;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Traits\Macroable;

/**
 * The file to manipulate.
 *
 */
class File
{
    use Macroable;
    use Concerns\AddsLines;
    use Concerns\AddsStubs;

    /**
     * Whether the file was created.
     *
     * @var bool
     */
    protected $wasCreated;

    /**
     * The original content.
     *
     * @var string|null
     */
    protected $originalContent;

    /**
     * The reason why the file needs to be updated manually.
     *
     * @var string|null
     */
    protected $manualUpdateReason;

    /**
     * Instantiate the class.
     *
     * @param string $path
     */
    public function __construct(protected string $path)
    {
        $this->wasCreated = !file_exists($path);
        $this->originalContent = $this->wasCreated ? null : file_get_contents($path);
    }

    /**
     * Retrieve the file path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retrieve the file relative path
     *
     * @return string
     */
    public function getRelativePath(): string
    {
        $basePath = App::basePath();

        return substr($this->path, strlen($basePath) + 1);
    }

    /**
     * Determine whether the file was created
     *
     * @return bool
     */
    public function wasCreated(): bool
    {
        return $this->wasCreated;
    }

    /**
     * Determine whether the file was updated
     *
     * @return bool
     */
    public function wasUpdated(): bool
    {
        return !$this->wasCreated();
    }

    /**
     * Retrieve the original content
     *
     * @return string|null
     */
    public function getOriginalContent(): ?string
    {
        return $this->originalContent;
    }

    /**
     * Retrieve the reason why the file needs to be updated manually
     *
     * @return string|null
     */
    public function getManualUpdateReason(): ?string
    {
        return $this->manualUpdateReason;
    }

    /**
     * Set the reason why the file needs to be updated manually
     *
     * @param string $reason
     * @return self
     */
    public function needsManualUpdateTo(string $reason): static
    {
        $this->manualUpdateReason = $reason;

        return $this;
    }

    /**
     * Determine whether the file needs to be updated manually
     *
     * @return bool
     */
    public function needsManualUpdate(): bool
    {
        return isset($this->manualUpdateReason);
    }
}
