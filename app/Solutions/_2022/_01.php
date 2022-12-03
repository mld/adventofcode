<?php

namespace App\Solutions\_2022;

use App\Solution;

class _01 extends Solution
{

    protected array $elves = [];

    public function __construct(array $attributes = [])
    {
        // $this->year and $this->day is set through attributes, and $this->raw is populated from puzzle input
        parent::__construct($attributes);

        // Todo: Add any manipulations to the raw input that you would like
        $elf = 0;
        foreach ($this->getLinesFromRaw() as $line) {
            if (strlen(trim($line)) == 0) {
                $elf++;
                continue;
            }

            $this->elves[$elf][] = (int)trim($line);
        }
    }

    public function part1(): int
    {
        $elfSums = [];

        foreach (array_keys($this->elves) as $elf) {
            $elfSums[$elf] = array_sum($this->elves[$elf]);
        }
        return (max($elfSums));
    }

    public function part2(): int
    {
        $elfSums = [];

        foreach (array_keys($this->elves) as $elf) {
            $elfSums[$elf] = array_sum($this->elves[$elf]);
        }

        rsort($elfSums, SORT_NUMERIC);

        $topThreeElves = array_slice($elfSums, 0, 3);

        return (array_sum($topThreeElves));
    }
}
