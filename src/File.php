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
    use Concerns\DataAware;

    /**
     * Whether the file was created.
     *
     * @var bool
     */
    protected bool $wasCreated;

    /**
     * The original content.
     *
     * @var string|null
     */
    protected ?string $originalContent;

    /**
     * The reasons why the file needs to be updated manually.
     *
     * @var string[]
     */
    protected array $manualUpdateReasons = [];

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
     * Statically instantiate the class
     *
     * @param string $path
     * @return static
     */
    public static function from(string $path): static
    {
        return new static($path);
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
     * Set the reason why the file needs to be updated manually
     *
     * @param string $reason
     * @return static
     */
    public function needsManualUpdateTo(string $reason): static
    {
        $this->manualUpdateReasons[] = $reason;

        return $this;
    }

    /**
     * Retrieve the reason why the file needs to be updated manually
     *
     * @return string
     */
    public function getManualUpdateReason(): string
    {
        preg_match_all('/@todo\s+(.+(?: *\w+))/', file_get_contents($this->getPath()), $matches);

        $reasons = [
            ...$this->manualUpdateReasons,
            ...$matches[1],
        ];

        return collect($reasons)->join(', ', ' and ');
    }
}
