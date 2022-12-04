<?php

namespace App\Solutions\_2022;

use App\Solution;

class _04 extends Solution
{
    protected array $list = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->list = [];
        foreach ($this->getLinesFromRaw() as $line) {
            $pairs = explode(',', $line);
            $sections[0] = explode('-', $pairs[0]);
            $sections[1] = explode('-', $pairs[1]);
            $this->list[] =
                [
                    range($sections[0][0], $sections[0][1]),
                    range($sections[1][0], $sections[1][1])
                ];
        }
    }

    public function part1(): int
    {
        $fully = [];
        foreach ($this->list as $key => $ranges) {
            $inBothRanges = array_intersect(...$ranges);

            foreach (array_keys($ranges) as $item) {
                $diff = array_diff($ranges[$item], $inBothRanges);
                if (count($diff) == 0) {
                    $fully[$key] = $ranges[$item];
                }
            }
        }

        return count($fully);
    }

    public function part2(): int
    {
        $overlap = [];
        foreach ($this->list as $key => $ranges) {
            $inBothRanges = array_intersect(...$ranges);

            if (count($inBothRanges) > 0) {
                $overlap[$key] = $inBothRanges;
            }
        }

        return count($overlap);
    }
}
