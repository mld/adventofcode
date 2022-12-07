<?php

namespace App\Solutions\_2022;

use App\Helpers\_2022\Directory;
use App\Solution;

class _07 extends Solution
{

    public const ROOT = '';
    public const SMALL_DIRECTORY = 100000;
    public const AVAILABLE_SPACE = 70000000;
    public const NEEDED_SPACE = 30000000;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected function du(): array
    {
        $directories[self::ROOT] = new Directory(self::ROOT);
        $cwd = &$directories[self::ROOT];

        // Walk through all commands, creating a file tree
        foreach ($this->getLinesFromRaw() as $line) {
            if (strlen(trim($line)) == 0) {
                continue;
            }

            $command = explode(' ', $line);
            if ($command[0] == '$') {
                if ($command[1] == 'cd') {
                    $target = $command[2];

                    if ($target == '/') {
                        $cwd = &$directories[self::ROOT];
                    } elseif ($target == '..' && $cwd->name != self::ROOT) {
                        $cwd = &$directories[$cwd->parent];
                    } else {
                        $cwd = &$directories[$cwd->name . DIRECTORY_SEPARATOR . $target];
                    }

                    $this->debug(sprintf("cd %s\n", $cwd->name));
                } elseif ($command[1] == 'ls') {
                    $this->debug(sprintf("\nls %s\n", $cwd->name));
                    // output from this command is handled in the next elseif+else
                }
            } elseif ($command[0] == 'dir') {
                // create the directory as soon as we see it
                $name = $cwd->name . DIRECTORY_SEPARATOR . $command[1];
                $directories[$name] = new Directory($name, $cwd->name, $cwd->depth + 1);
                $this->debug(sprintf("  d %s\n", $name));
            } else {
                // add files to current directory
                $cwd->addFile($command[1], (int)$command[0]);
                $this->debug(sprintf("  f %s%s%s (%d)\n", $cwd->name, DIRECTORY_SEPARATOR, $command[1], $command[0]));
            }

        }

        // Find the deepest level of the tree
        $maxDepth = array_reduce($directories, function (int $carry, Directory $item) {
            return ($item->depth > $carry) ? $item->depth : $carry;
        }, PHP_INT_MIN);

        // Walk through all directories from the deepest leaf to the root, one level at a time
        foreach (range($maxDepth, 0, -1) as $depth) {
            $level = array_filter($directories, function (Directory $item) use ($depth) {
                return $item->depth == $depth;
            });
            foreach ($level as $directory) {
                $cwd = &$directories[$directory->name];
                $cwd->totalSize += $cwd->size;

                // don't try to add root's size to a non-existing parent
                if (!is_null($cwd->parent)) {
                    $parent = &$directories[$cwd->parent];
                    $parent->totalSize += $cwd->totalSize;
                }
            }
        }

        return $directories;
    }


    public function part1(): int
    {
        $directories = $this->du();

        return array_reduce($directories, function (int $carry, Directory $item) {
            // If this is a small enough directory, add it to the total, otherwise skip ut
            return $item->totalSize < self::SMALL_DIRECTORY ? $item->totalSize + $carry : $carry;
        }, 0);
    }

    public function part2(): int
    {
        $directories = $this->du();

        $deleteAtLeast = self::NEEDED_SPACE + $directories[self::ROOT]->totalSize - self::AVAILABLE_SPACE;

        return array_reduce($directories, function (int $carry, Directory $item) use ($deleteAtLeast) {
            // If the current directory is large enough to free up enough space by itself _and_ is smaller than anything we've found before, lets go with it
            if ($item->totalSize >= $deleteAtLeast && $item->totalSize < $carry) {
                return $item->totalSize;
            }
            return $carry;
        }, PHP_INT_MAX);
    }
}
