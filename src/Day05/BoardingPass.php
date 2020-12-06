<?php

namespace App\Day05;

class BoardingPass
{

    /** @var array<mixed> */
    protected $list;

    public const ROWS = 128;
    public const SEATS_PER_ROW = 8;

    /**
     * Passports constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        $this->parseInput($input);
    }

    /**
     * @param array<string> $input
     */
    public function parseInput(array $input): void
    {
        $this->list = [];
        foreach ($input as $row) {
            $filteredInput = filter_var(
                trim($row),
                FILTER_VALIDATE_REGEXP,
                ['options' => ['regexp' => '|^[FB]{7}[LR]{3}$|']]
            );
            if ($filteredInput !== false) {
                $this->list[(string)$filteredInput] = self::getSeating((string)$filteredInput);
            }
        }
    }

    /**
     * @param string $boardingpass
     * @return array<int>
     */
    public static function getSeating(string $boardingpass): array
    {
        $parts = str_split($boardingpass);
        $row['min'] = 0;
        $row['max'] = self::ROWS - 1;
        $column['min'] = 0;
        $column['max'] = self::SEATS_PER_ROW - 1;

        foreach ($parts as $part) {
            switch ($part) {
                case 'F': // front
                    $row['max'] = $row['max'] - ($row['max'] - $row['min'] + 1) / 2;
                    printf("%s: Keeping rows %d through %d\n", $part, $row['min'], $row['max']);
                    break;
                case 'B': // back
                    $row['min'] = $row['min'] + ($row['max'] - $row['min'] + 1) / 2;
                    printf("%s: Keeping rows %d through %d\n", $part, $row['min'], $row['max']);
                    break;
                case 'L': // left
                    $column['max'] = $column['max'] - ($column['max'] - $column['min'] + 1) / 2;
                    printf("%s: Keeping columns %d through %d\n", $part, $column['min'], $column['max']);
                    break;
                case 'R': // right
                    $column['min'] = $column['min'] + ($column['max'] - $column['min'] + 1) / 2;
                    printf("%s: Keeping columns %d through %d\n", $part, $column['min'], $column['max']);
                    break;
            }
        }
        printf("Row: %d, Column: %d, Seat: %d\n", $row['min'], $column['min'], ($row['min'] * 8 + $column['min']));
        return [
            'row' => (int)$row['min'],
            'column' => (int)$column['min'],
            'seat' => (int)($row['min'] * 8 + $column['min'])
        ];
    }

    public function getHighestSeatId(): int
    {
        $max = 0;
        foreach ($this->list as $item) {
            if ($item['seat'] > $max) {
                $max = $item['seat'];
            }
        }
        return $max;
    }

    public function findMissingSeat(): int
    {
        $seats = [];
        foreach ($this->list as $boardingpass) {
            $seats[$boardingpass['seat']] = true;
        }

        ksort($seats);

        for ($seat = min(array_keys($seats)); $seat < max(array_keys($seats)); $seat++) {
            if (isset($seats[$seat])) {
                printf("Skipping seat %d - it is occupied\n", $seat);
                continue;
            }

            if (!isset($seats[$seat - 1])) {
                printf("Skipping seat %d - seat %d isn't occupied\n", $seat, $seat - 1);
                continue;
            }

            if (!isset($seats[$seat + 1])) {
                printf("Skipping seat %d - seat %d isn't occupied\n", $seat, $seat + 1);
                continue;
            }

            return $seat;
        }
        return -1;
    }
}
