<?php

namespace App\Day07;

class HandyHaversacks
{
    /** @var array<mixed> */
    protected array $bags;
    /** @var array<mixed> */
    protected array $parents;

    /**
     * Passports constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->bags = [];
        foreach ($rows as $row) {
            sscanf(trim($row), '%s %s bags contain ', $pattern, $color);
            $bag = sprintf('%s %s', $pattern, $color);

            $parts = explode(' contain ', trim($row));
            if ($parts === false) {
                continue;
            }

            $contains = trim($parts[1]);

            if ($contains == 'no other bags.') {
                continue;
            }

            $this->bags[$bag] = [];
            $containedBags = explode(',', $contains);
            foreach ($containedBags as $item) {
                sscanf(trim($item), '%d %s %s bag', $number, $pattern, $color);
                $child = sprintf('%s %s', $pattern, $color);
                $this->bags[$bag] = array_merge($this->bags[$bag], array_fill(0, $number, $child));
                $this->parents[$child][] = $bag;
            }
        }

        $this->printRules();
    }

    public function bagsThatMayContainBag(string $needle): int
    {
        $found = $this->findParents($needle);
        $count = count($found);
        if (in_array($needle, $found)) {
            $count--;
        }
        print_r($found);
        return $count;
    }

    public function bagsInBag(string $needle): int
    {
        $found = $this->findChildren($needle);
        $count = count($found);
        if (in_array($needle, $found)) {
            $count--;
        }
        return $count;
    }

    public function findParents(string $needle): array
    {
        $found = [$needle];
        if (!isset($this->parents[$needle])) {
            return $found;
        }

        foreach ($this->parents[$needle] as $parent) {
            $found = array_merge($found, $this->findParents($parent));
        }
        return array_unique($found);
    }

    public function findChildren(string $needle): array
    {
        $found = [$needle];
        if (!isset($this->bags[$needle])) {
            return $found;
        }

        foreach ($this->bags[$needle] as $bag) {
            $found = array_merge($found, $this->findChildren($bag));
        }
        return $found;
    }

    public function printRules(): void
    {
        printf("\n\n");
        foreach ($this->bags as $key => $item) {
            printf("%s:\n", $key);
            if (count($item) == 0) {
                printf(" * Can't contain any more bags\n");
            }
            foreach ($item as $v) {
                printf(" * %s\n", $v);
            }
        }

        printf("\n\n");
        foreach ($this->parents as $key => $item) {
            printf("%s:\n", $key);
            if (count($item) == 0) {
                printf(" * Doesn't live in any bags\n");
            }
            foreach ($item as $v) {
                printf(" * %s\n", $v);
            }
        }
    }
}
