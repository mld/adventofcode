<?php

namespace App\Day24;

class LobbyLayout
{
    public const PART2_DAYS = 100;

    /**
     * @var array<string>
     */
    protected array $input;

    /**
     * LobbyLayout constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function part1(): int
    {
        $map = $this->getMap();

        return $this->countBlackTiles($map);
    }

    public function part2(): int
    {
        $map = $this->getMap();

        for ($n = 0; $n < self::PART2_DAYS; $n++) {
            $copy = $map;

            $d = $this->getMapDimensions($map);

            for ($x = $d['x']['min'] - 1; $x <= $d['x']['max'] + 1; $x++) {
                for ($z = $d['z']['min'] - 1; $z <= $d['z']['max'] + 1; $z++) {
                    $neighbours = 0;
                    $neighbours += $map[$x + 1][$z] ?? 0;
                    $neighbours += $map[$x][$z + 1] ?? 0;
                    $neighbours += $map[$x - 1][$z + 1] ?? 0;
                    $neighbours += $map[$x - 1][$z] ?? 0;
                    $neighbours += $map[$x][$z - 1] ?? 0;
                    $neighbours += $map[$x + 1][$z - 1] ?? 0;

                    if (isset($map[$x][$z]) && ($neighbours == 0 || $neighbours > 2)) {
                        unset($copy[$x][$z]);
                    } elseif ((!isset($map[$x][$z])) && $neighbours == 2) {
                        $copy[$x][$z] = 1;
                    }
                }
            }
            $map = $copy;
        }

        return $this->countBlackTiles($map);
    }

    /**
     * @return array<mixed>
     */
    protected function getMap(): array
    {
        $map = [];
        foreach ($this->input as $path) {
            $path = str_split(trim($path));
            if (count($path) == 0) {
                continue;
            }

            $x = 0;
            $z = 0;
            do {
                $direction = array_shift($path);
                if (in_array($direction, ['s', 'n'])) {
                    $direction .= array_shift($path);
                }

                switch ($direction) {
                    case 'e':
                        $x++;
                        break;
                    case 'se':
                        $z++;
                        break;
                    case 'sw':
                        $x--;
                        $z++;
                        break;
                    case 'w':
                        $x--;
                        break;
                    case 'nw':
                        $z--;
                        break;
                    case 'ne':
                        $x++;
                        $z--;
                        break;
                }
            } while (count($path) > 0);

            if (isset($map[$x][$z])) {
                unset($map[$x][$z]);
            } else {
                $map[$x][$z] = 1;
            }
        }
        return $map;
    }

    /**
     * @param array<int,array> $map
     * @return int
     */
    protected function countBlackTiles(array $map): int
    {
        $blackTiles = 0;
        foreach (array_keys($map) as $x) {
            $blackTiles += array_sum($map[$x]);
        }
        return (int)$blackTiles;
    }

    /**
     * @param array<int,array> $map
     * @return array<string,array<string,int>>
     */
    protected function getMapDimensions(array $map): array
    {
        // find the black tiles and include a border
        $blackTiles = [
            'x' => ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN],
            'z' => ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN]
        ];
        foreach (array_keys($map) as $x) {
            if ($x < $blackTiles['x']['min']) {
                $blackTiles['x']['min'] = $x;
            }
            if ($x > $blackTiles['x']['max']) {
                $blackTiles['x']['max'] = $x;
            }
            foreach (array_keys($map[$x]) as $z) {
                if ($z < $blackTiles['z']['min']) {
                    $blackTiles['z']['min'] = $z;
                }
                if ($z > $blackTiles['z']['max']) {
                    $blackTiles['z']['max'] = $z;
                }
            }
        }
        return $blackTiles;
    }
}
