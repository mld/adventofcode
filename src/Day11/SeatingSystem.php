<?php

namespace App\Day11;

class SeatingSystem
{
    /** @var array<mixed> */
    protected array $map;

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
        $this->map = [];
        foreach ($rows as $y => $row) {
            if (strlen(trim($row)) > 0) {
                $this->map[$y] = str_split(trim($row));
            }
        }
    }

    public function isSeatAvailable(int $x, int $y, bool $simple = true): bool
    {
        // outside of map
        if (!isset($this->map[$y][$x])) {
//            printf("(%d,%d) doesn't exist!\n", $x, $y);
            return false;
        }
        // floor
        if ($this->map[$y][$x] == '.') {
//            printf("(%d,%d) floor\n", $x, $y);
            return false;
        }

        $adjacentSeatsTaken = 0;

        $adjacentSeats = [
            [$x - 1, $y - 1],
            [$x, $y - 1],
            [$x + 1, $y - 1],
            [$x - 1, $y],
            [$x + 1, $y],
            [$x - 1, $y + 1],
            [$x, $y + 1],
            [$x + 1, $y + 1],
        ];

        if (!$simple) {
            $adjacentSeats = $this->findVisibleSeats($x, $y);
        }

//        printf("(%d,%d):\n", $x, $y);

        foreach ($adjacentSeats as $position) {
            $status = isset($this->map[$position[1]][$position[0]]) ? $this->map[$position[1]][$position[0]] : '.';
//            printf("  (%d,%d): %s\n", $x, $y, $status);
            if ($status == '#') {
                $adjacentSeatsTaken++;
            }
        }

//        printf("(%d,%d) %d adjacent seats taken\n", $x, $y, $adjacentSeatsTaken);

        if ($this->map[$y][$x] == 'L') {
            if ($adjacentSeatsTaken == 0) {
                return true;
            }
            return false;
        }
        if ($this->map[$y][$x] == '#') {
            if (($simple && $adjacentSeatsTaken < 4) || (!$simple && $adjacentSeatsTaken < 5)) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @return int[]
     */
    public function checkSeatingStatus(): array
    {
        $return = ['.' => 0, 'L' => 0, '#' => 0];
        foreach (array_keys($this->map) as $y) {
            foreach (array_keys($this->map[$y]) as $x) {
                $return[$this->map[$y][$x]]++;
            }
        }
        return $return;
    }

    public function movePeopleAround(bool $simple = true): void
    {
        $newMap = array_merge($this->map);
        foreach (array_keys($this->map) as $y) {
            foreach (array_keys($this->map[$y]) as $x) {
                if (in_array($this->map[$y][$x], ['#', 'L'])) {
                    $newMap[$y][$x] = $this->isSeatAvailable((int)$x, (int)$y, $simple) ? '#' : 'L';
                }
            }
        }
        $this->map = array_merge($newMap);
    }

    /**
     * @param int $x
     * @param int $y
     * @return array<mixed>
     */
    public function findVisibleSeats(int $x, int $y): array
    {
        $directions = [
            [-1, -1],
            [0, -1],
            [1, -1],
            [-1, 0],
            [1, 0],
            [-1, 1],
            [0, 1],
            [1, 1],
        ];

        $visibleSeats = [];
        foreach ($directions as $direction) {
            $pos = [$x, $y];
            $found = false;
            do {
                $pos[0] += $direction[0];
                $pos[1] += $direction[1];

                if (isset($this->map[$pos[1]][$pos[0]]) && in_array($this->map[$pos[1]][$pos[0]], ['#', 'L'])) {
                    $visibleSeats[] = $pos;
                    $found = true;
                }
            } while (isset($this->map[$pos[1]][$pos[0]]) && $found != true);
        }

//        $this->printDirections($x,$y,$visibleSeats);
//        printf("(%d,%d) %s", $x, $y, print_r($visibleSeats, true));

        return $visibleSeats;
    }

    /**
     * @param int $x
     * @param int $y
     * @param array<mixed> $directions
     */
    protected function printDirections(int $x, int $y, array $directions): void
    {
        printf("(%d,%d): ", $x, $y);
        foreach ($directions as $item) {
            printf("(%d,%d),", $item[0], $item[1]);
        }
        printf("\n");
    }

    public function part1(): int
    {
        do {
            $previousSeats = $this->checkSeatingStatus();
            $this->movePeopleAround();
            $seats = $this->checkSeatingStatus();
        } while (
            $previousSeats['.'] != $seats['.'] ||
            $previousSeats['L'] != $seats['L'] ||
            $previousSeats['#'] != $seats['#']
        );

        $seats = $this->checkSeatingStatus();
        print_r($seats);
        return $seats['#'];
    }

    public function part2(): int
    {
        $this->printMap();
        printf("\n\n");

        do {
            $previousSeats = $this->checkSeatingStatus();
            $this->movePeopleAround(false);
            $seats = $this->checkSeatingStatus();
            print_r($seats);
            $this->printMap();
            printf("\n\n");
        } while (
            $previousSeats['.'] != $seats['.'] ||
            $previousSeats['L'] != $seats['L'] ||
            $previousSeats['#'] != $seats['#']
        );

        $seats = $this->checkSeatingStatus();
        print_r($seats);
        return $seats['#'];
    }

    public function printMap(): void
    {
        foreach (array_keys($this->map) as $y) {
            foreach (array_keys($this->map[$y]) as $x) {
                printf(" %s", $this->map[$y][$x]);
            }
            printf("\n");
        }
    }
}
