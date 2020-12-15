<?php

namespace App\Day09;

class EncodingError
{
    /** @var array<int> */
    protected array $stack;

    protected int $step = 0;

    protected int $bufferSize = 0;

    /**
     * Passports constructor.
     * @param array<string> $rows
     * @param int $bufferSize
     */
    public function __construct(array $rows, int $bufferSize = 25)
    {
        $this->bufferSize = $bufferSize;
        $this->parseInput($rows);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->stack = [];
        foreach ($rows as $row) {
            if (strlen(trim($row)) > 0) {
                $this->stack[] = (int)trim($row);
            }
        }
        printf("Found %d numbers\n", count($this->stack));
    }

    public function findFirstInvalid(): int
    {
        while ($this->step()) {
            $this->step++;
        }
        printf("Found %d on step %d\n", $this->stack[$this->bufferSize + $this->step], $this->step);
        return ($this->stack[$this->bufferSize + $this->step]);
    }

    public function step(): bool
    {
        $work = array_slice($this->stack, $this->step, $this->bufferSize, true);
        $next = $this->stack[$this->bufferSize + $this->step];

        foreach ($work as $key => $item) {
            $mapped = array_map(function ($value) use ($item) {
                return $value + $item;
            }, $work);
//            printf("%d: %s\n", $item, print_r($mapped, true));
            unset($mapped[$key]); // we can't use the same value twice
            if (in_array($next, $mapped)) {
//                printf("%s found! Moving on. (%d)\n", $next, $item);
                return true;
            }
        }
        return false;
    }

    public function findEncryptionWeakness(): int
    {
        $needle = $this->findFirstInvalid();
        $start = 0;
        do {
            $sum = $this->findContiguousSum($needle, array_slice($this->stack, $start));
            if ($sum == -1) {
                $start++;
            }
        } while ($sum == -1);
        return $sum;
    }

    /**
     * @param int $needle
     * @param array<int> $slice
     * @return int
     */
    public function findContiguousSum(int $needle, array $slice): int
    {
        $sum = 0;
        $items = [];
        foreach ($slice as $item) {
            $items[] = $item;
            $sum += $item;
            if ($needle == $sum) {
                sort($items);
                printf("Found %d in %s\n", $needle, join(',', $items));
                return ($items[0] + max($items));
            }
            if ($needle < $sum) {
                return -1;
            }
        }
        return -1;
    }
}
