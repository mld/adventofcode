<?php

namespace App\Day10;

class AdapterArray
{
    /** @var array<int> */
    protected array $stack;

    protected int $adapterVoltage = 0;

    protected int $accumulator = 0;

    /**
     * @var array<mixed>
     */
    protected array $cache;

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
        $this->stack = [];
        foreach ($rows as $row) {
            $jolts = (int)trim($row);
            if ($jolts > 0) {
                $this->stack[] = $jolts;
            }
        }
        // add own adapter
        $this->stack[] = (max($this->stack) + 3);
        sort($this->stack);
        print_r($this->stack);
        printf("Found %d commands\n", count($this->stack));
    }

    /**
     * @return int[]
     */
    public function findJoltDifferences(): array
    {
//        $result = [];
        $stack = $this->stack;
        $currentVoltage = 0;
        $voltageDiff = [1 => 0, 2 => 0, 3 => 0];
        while (count($stack) > 0) {
            $voltage = array_shift($stack);
            $diff = $voltage - $currentVoltage;
//            $result[$voltage] = $diff;
            $voltageDiff[$diff]++;

            printf("Adapter %d has diff of %d to previous (%d)\n", $voltage, $diff, $currentVoltage);
            $currentVoltage = $voltage;
        }
        print_r($voltageDiff);
        return $voltageDiff;
    }

    /**
     * @param array<int> $input
     * @param array<int> $cache
     * @param int $start
     * @param int $depth
     * @return int
     */
    public function numberOfPaths(array $input, array &$cache, int $start = 0, int $depth = 0): int
    {
//        printf("%d Running %d: %s\n", $depth, $start, join(',', $input));
        if (isset($cache[$start])) {
            return $cache[$start];
        }
        $count = count($input);
        if ($count == 0 || $count == 1) {
            printf("[%3d] %d returned %d (%s)\n", $depth, -1, $count, join(',', $input));
            $cache[$start] = $count;
            return $count;
        }

        $count = 0;
        $next = array_shift($input);

        while ($next !== null && ($next - $start) <= 3) {
            $n = $this->numberOfPaths($input, $cache, $next, $depth + 1);
            $count += $n;
            printf("[%3d] %d returned %d (%s)\n", $depth, $next, $n, join(',', $input));
            $next = array_shift($input);
        }

        $cache[$start] = $count;
        return $count;
    }

    public function part1(): int
    {
        $diff = $this->findJoltDifferences();
        return ($diff[1] * $diff[3]);
    }

    public function part2(): int
    {
        $cache = [];
        $combinations = $this->numberOfPaths($this->stack, $cache);
        return $combinations;
    }

    public function printStack(int $mark = -1): void
    {
        foreach ($this->stack as $key => $value) {
//            $current = ($key == $this->step ? ' => ' : '    ');
//            if ($key == $mark) {
//                $current[0] = '*';
//            }
            printf("%03d\n", $value);
        }
    }
}
