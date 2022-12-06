<?php

namespace App\Solutions\_2022;

use App\Solution;

class _06 extends Solution
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected function findUniqueSequence(array $buffer, int $numberOfCharacters = 4): int
    {
        $sequence = [];
        for ($n = 0; $n < count($buffer); $n++) {
            $sequence[] = $buffer[$n];
            if (count($sequence) > $numberOfCharacters) {
                array_shift($sequence);
            }
            $this->debug(sprintf("%2d: %s\n", $n + 1, join('', $sequence)));
            if (count(array_unique($sequence)) == $numberOfCharacters) {
                return $n + 1;
            }
        }

        return -1;
    }

    public function part1(): int
    {
        $buffer = $this->getArrayFromRaw();
        return $this->findUniqueSequence($buffer[0]);
    }

    public function part2(): int
    {
        $buffer = $this->getArrayFromRaw();
        return $this->findUniqueSequence($buffer[0], 14);
    }
}
