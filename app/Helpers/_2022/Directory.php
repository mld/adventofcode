<?php

namespace App\Helpers\_2022;

/**
 * Helper class for 2022/7
 */
class Directory
{
    public string $name;
    public array $files;
    public int $size;
    public int $totalSize;
    /**
     * @var string|null Parent name (or null for root)
     */
    public mixed $parent;
    public int $depth;

    function __construct(string $name = '', string $parent = null, int $depth = 0)
    {
        $this->name = $name;
        $this->parent = $parent;
        $this->files = [];
        $this->size = 0;
        $this->totalSize = 0;
        $this->depth = $depth;
    }

    public function addFile($filename, $size): bool
    {
        if (isset($this->files[$filename]) !== false) {
            return false;
        }
        $this->files[$filename] = $size;
        $this->size += $size;
        return true;
    }
}
