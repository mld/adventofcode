<?php

namespace App\Day15;

class RambuctiousRecitation
{
    /**
     * @var array<int>
     */
    protected array $input;

    protected int $part1;
    protected int $part2;

    /** @var array<int> */
    protected array $occurrences;

    /**
     * RainRisk constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->input = [];
        foreach (explode(',', $rows[0]) as $value) {
            $this->input[] = (int)$value;
        }
    }

    /**
     * @param array<int> $input
     * @param int $findStep
     * @return int
     */
    public function parse(array $input, int $findStep): int
    {
        $step = 0;
        $current = -1;
        $occurrences = [];

        // handle starting numbers
        foreach ($input as $value) {
            $step++;
            $current = (int)$value;
            $occurrences[$current][] = $step;
        }

        // start "guessing"
        do {
            $lastNumber = $current;
            $step++;
            $lastTwo = array_slice($occurrences[$lastNumber], -2);

            if (count($lastTwo) < 2) {
                $current = 0;
            } else {
                $current = $lastTwo[1] - $lastTwo[0];
            }

            $occurrences[$current][] = $step;
            if (count($occurrences[$current]) > 2) {
                $occurrences[$current] = array_slice($occurrences[$current], -2);
            }
        } while ($step < $findStep);

        return $current;
    }

    public function part1(): int
    {
        return $this->parse($this->input, 2020);
    }

    public function part2(): int
    {
        return $this->parse($this->input, 30000000);
    }
}
