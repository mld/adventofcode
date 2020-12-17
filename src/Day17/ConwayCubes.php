<?php

namespace App\Day17;

class ConwayCubes
{
    /** @var array<mixed> */
    protected array $map;

    /**
     * TobogganTrajectory constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
        $this->printMap();
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $w = 0;
        $z = 0;
        $y = 0;
        foreach ($rows as $row) {
            if (strlen(trim($row)) > 0) {
                $this->map[$w][$z][$y] = str_split(trim($row));
                $y++;
            }
        }
    }

    public function printMap(): void
    {
        foreach (array_keys($this->map) as $w) {
            foreach (array_keys($this->map[$w]) as $z) {
                printf("z=%d, w=%d\n", $z, $w);
                foreach (array_keys($this->map[$w][$z]) as $y) {
                    foreach (array_keys($this->map[$w][$z][$y]) as $x) {
                        printf("%s ", $this->map[$w][$z][$y][$x]);
                    }
                    printf("\n");
                }
            }
            printf("\n");
        }
    }

    protected function countActiveNeighbours(
        int $px = 0,
        int $py = 0,
        int $pz = 0,
        int $pw = 0,
        int $dimensions = 3
    ): int {
        $active = 0;
        for ($x = $px - 1; $x <= $px + 1; $x++) {
            for ($y = $py - 1; $y <= $py + 1; $y++) {
                for ($z = $pz - 1; $z <= $pz + 1; $z++) {
                    for ($w = $pw - 1; $w <= $pw + 1; $w++) {
                        if ($dimensions == 3 && $w != $pw) {
                            continue;
                        }
                        if ($px == $x && $py == $y && $pz == $z && $pw == $w) {
                            // we don't count our own point
                            continue;
                        }
                        if (isset($this->map[$w][$z][$y][$x]) && $this->map[$w][$z][$y][$x] == '#') {
                            $active++;
                        }
                    }
                }
            }
        }
        return $active;
    }

    /**
     * @return array[]
     */
    public function findMaxMin(): array
    {
        $return = [
            'x' => [PHP_INT_MAX, PHP_INT_MIN],
            'y' => [PHP_INT_MAX, PHP_INT_MIN],
            'z' => [PHP_INT_MAX, PHP_INT_MIN],
            'w' => [PHP_INT_MAX, PHP_INT_MIN],
        ];
        foreach (array_keys($this->map) as $w) {
            foreach (array_keys($this->map[$w]) as $z) {
                foreach (array_keys($this->map[$w][$z]) as $y) {
                    foreach (array_keys($this->map[$w][$z][$y]) as $x) {
                        if (isset($this->map[$w][$z][$y][$x]) && $this->map[$w][$z][$y][$x] == '#') {
                            if ($x < $return['x'][0]) {
                                $return['x'][0] = $x;
                            }
                            if ($x > $return['x'][1]) {
                                $return['x'][1] = $x;
                            }
                            if ($y < $return['y'][0]) {
                                $return['y'][0] = $y;
                            }
                            if ($y > $return['y'][1]) {
                                $return['y'][1] = $y;
                            }
                            if ($z < $return['z'][0]) {
                                $return['z'][0] = $z;
                            }
                            if ($z > $return['z'][1]) {
                                $return['z'][1] = $z;
                            }
                            if ($w < $return['w'][0]) {
                                $return['w'][0] = $w;
                            }
                            if ($w > $return['w'][1]) {
                                $return['w'][1] = $w;
                            }
                        }
                    }
                }
            }
        }
        return $return;
    }

    public function part1(): int
    {
        return $this->part();
    }

    public function part2(): int
    {
        return $this->part(4);
    }

    public function part(int $dimensions = 3): int
    {
        $cycles = 0;
        do {
            $cycles++;
            $this->stepMap($dimensions);
        } while ($cycles < 6);

        return $this->countActive();
    }

    /**
     * @return int
     */
    public function countActive(): int
    {
        $active = 0;
        foreach (array_keys($this->map) as $w) {
            foreach (array_keys($this->map[$w]) as $z) {
                foreach (array_keys($this->map[$w][$z]) as $y) {
                    foreach (array_keys($this->map[$w][$z][$y]) as $x) {
                        if (isset($this->map[$w][$z][$y][$x]) && $this->map[$w][$z][$y][$x] == '#') {
                            $active++;
                        }
                    }
                }
            }
        }
        return $active;
    }

    public function stepMap(int $dimensions = 3): void
    {
        $limits = $this->findMaxMin();
        $newMap = $this->map;
        for ($x = ($limits['x'][0] - 1); $x <= ($limits['x'][1] + 1); $x++) {
            for ($y = ($limits['y'][0] - 1); $y <= ($limits['y'][1] + 1); $y++) {
                for ($z = ($limits['z'][0] - 1); $z <= ($limits['z'][1] + 1); $z++) {
                    for ($w = ($limits['w'][0] - 1); $w <= ($limits['w'][1] + 1); $w++) {
                        if ($dimensions == 3 && $w != 0) {
                            continue;
                        }
                        $active = $this->countActiveNeighbours((int)$x, (int)$y, (int)$z, (int)$w, $dimensions);

                        if (isset($this->map[$w][$z][$y][$x]) && $this->map[$w][$z][$y][$x] == '#') {
                            $newMap[$w][$z][$y][$x] = ($active == 2 || $active == 3) ? '#' : '.';
                        } else {
                            $newMap[$w][$z][$y][$x] = ($active == 3) ? '#' : '.';
                        }
                    }
                }
            }
        }
        $this->map = $newMap;
    }
}
