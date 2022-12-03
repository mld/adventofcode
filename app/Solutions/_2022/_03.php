<?php

namespace App\Solutions\_2022;

use App\Solution;

class _03 extends Solution
{

    protected array $priorities = [];

    public function __construct(array $attributes = [])
    {
        // $this->year and $this->day is set through attributes, and $this->raw is populated from puzzle input
        parent::__construct($attributes);

        $priority = 0;
        foreach (array_merge(range('a', 'z'), range('A', 'Z')) as $item) {
            $priority++;
            $this->priorities[$item] = $priority;
        }
    }

    protected function getShared(...$strings): array
    {
        $charCounts = [];
        foreach ($strings as $key => $item) {
            $charCounts[$key] = count_chars($item, 1);
        }
        $shared = [];
        $allHave = array_intersect_key(...$charCounts);
        foreach (array_keys($allHave) as $character) {
            $shared[] = chr($character);
        }
        return $shared;
    }

    public function part1(): int
    {
        $sum = 0;
        foreach ($this->getLinesFromRaw() as $sack) {
            $compartments = str_split($sack, intdiv(strlen($sack),2));
            foreach ($this->getShared(...$compartments) as $item) {
                $sum += $this->priorities[$item];
            }
        }
        return $sum;
    }

    public function part2(): int
    {
        $sum = 0;
        $group = [];
        foreach ($this->getLinesFromRaw() as $sack) {
            $group[] = $sack;
            $sacksInGroup = count($group);

            if ($sacksInGroup < 3) {
                continue;
            }

            foreach ($this->getShared(...$group) as $item) {
                $sum += $this->priorities[$item];
            }
            $group = [];
        }
        return $sum;
    }
}
