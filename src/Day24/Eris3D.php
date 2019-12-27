<?php


namespace App\Day24;


class Eris3D
{
    protected $debug;
    protected $map;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $this->map = [];
        $this->addLevel(0);

    }

    public function addLevel($level = 0)
    {
        foreach (range(-1, 5) as $x) {
            foreach (range(-1, 5) as $y) {
                if ($x == -1 || $x == 5) {
                    $this->map[$level][$x][$y] = ' ';
                } elseif ($y == -1 || $y == 5) {
                    $this->map[$level][$x][$y] = ' ';
                } elseif ($x == 2 && $y == 2) {
                    $this->map[$level][$x][$y] = '?';
                } else {
                    $this->map[$level][$x][$y] = '.';
                }
            }
        }
    }

    public function parseRaw($raw)
    {
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                if ($raw[$y][$x] == '#') {
                    $this->map[0][$x][$y] = '#';
                }
            }
        }
    }

    public function printMap()
    {
        ksort($this->map);
        $rating = 0;
        foreach (array_keys($this->map) as $level) {
            if ($this->countBugs($level) == 0) {
                // nothing here yet
                continue;
            }

            printf("Depth %d: ", $level);
            foreach (range(-1, 4) as $y) {
                printf(' ');
                foreach (range(-1, 5) as $x) {
                    printf("%s", $this->map[$level][$x][$y]);
                }
                printf("\n");
            }
            $layerRating = $this->countBugs($level);
            printf("Bugs: %3d\n\n", $layerRating);
            $rating += $layerRating;
        }

        printf("Layered bugs rating: %3d\n", $rating);
    }

    public function tick(): void
    {
        printf("Tick\n");
        // Build map of adjacent bugs
        $neighbours = [];
        $minLevel = min(array_keys($this->map));
        $minLevelRating = $this->countBugs($minLevel);
        while ($minLevelRating == 0) {
            $minLevel++;
            $minLevelRating = $this->countBugs($minLevel);
        }
        $maxLevel = max(array_keys($this->map));
        $maxLevelRating = $this->countBugs($maxLevel);
        while ($maxLevelRating == 0) {
            $maxLevel--;
            $maxLevelRating = $this->countBugs($maxLevel);
        }

//        printf("Min/max level: %d/%d\n", $minLevel, $maxLevel);

        foreach (range($minLevel - 1, $maxLevel + 1) as $level) {
            if (!isset($this->map[$level])) {
                $this->addLevel($level);
            }

            foreach (range(0, 4) as $x) {
                foreach (range(0, 4) as $y) {
                    if ($x == 2 && $y == 2) {
                        continue;
                    }
                    $neighbours[$level][$x][$y] = $this->findNeighbourBugs($level, $x, $y);
                }
            }
        }

        // Update map
        foreach (range($minLevel - 1, $maxLevel + 1) as $level) {
            foreach (range(0, 4) as $x) {
                foreach (range(0, 4) as $y) {
                    if ($x == 2 && $y == 2) {
                        continue;
                    }

                    if ($this->map[$level][$x][$y] == '#' && $neighbours[$level][$x][$y] != 1) {
                        $this->map[$level][$x][$y] = '.';
                    } elseif ($this->map[$level][$x][$y] == '.' && ($neighbours[$level][$x][$y] == 1 || $neighbours[$level][$x][$y] == 2)) {
                        $this->map[$level][$x][$y] = '#';
                    }
                }
            }
        }

    }

    public function countBugs($level = null)
    {
        $rating = 0;

        if (is_null($level)) {
            // Count from all layers
            foreach (array_keys($this->map) as $checkLevel) {
                $rating += $this->countBugs($checkLevel);
            }
            return $rating;
        }

        if (!isset($this->map[$level])) {
            // This layer doesn't exist (yet)
            return 0;
        }

        // Ok, counting selected layer
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                if ($this->map[$level][$x][$y] == '#') {
                    $rating++;
                }
            }
        }
        return $rating;
    }

    protected function findNeighbourBugs($level, $x, $y)
    {
        //     |     |         |     |
        //  1  |  2  |    3    |  4  |  5
        //     |     |         |     |
        //-----+-----+---------+-----+-----
        //     |     |         |     |
        //  6  |  7  |    8    |  9  |  10
        //     |     |         |     |
        //-----+-----+---------+-----+-----
        //     |     |A|B|C|D|E|     |
        //     |     |-+-+-+-+-|     |
        //     |     |F|G|H|I|J|     |
        //     |     |-+-+-+-+-|     |
        // 11  | 12  |K|L|?|N|O|  14 |  15
        //     |     |-+-+-+-+-|     |
        //     |     |P|Q|R|S|T|     |
        //     |     |-+-+-+-+-|     |
        //     |     |U|V|W|X|Y|     |
        //-----+-----+---------+-----+-----
        //     |     |         |     |
        // 16  | 17  |    18   |  19 |  20
        //     |     |         |     |
        //-----+-----+---------+-----+-----
        //     |     |         |     |
        // 21  | 22  |    23   |  24 |  25
        //     |     |         |     |
        $neighbours = 0;
        // Start with left
        if ($x == 0) { // ok
            // check level outside
            if (isset($this->map[$level - 1])) {
                $neighbours += $this->map[$level - 1][1][2] == '#' ? 1 : 0;
            }
        } elseif ($x == 3 && $y == 2) { // ok
            // check level inside
            if (isset($this->map[$level + 1])) {
                foreach (range(0, 4) as $yy) {
                    $neighbours += $this->map[$level + 1][4][$yy] == '#' ? 1 : 0;
                }
            }
        } else { // ok
            $neighbours += $this->map[$level][$x - 1][$y] == '#' ? 1 : 0;
        }

        // Then right
        if ($x == 4) { // ok
            // check level outside
            if (isset($this->map[$level - 1])) {
                $neighbours += $this->map[$level - 1][3][2] == '#' ? 1 : 0;
            }
        } elseif ($x == 1 && $y == 2) { // ok
            // check level inside
            if (isset($this->map[$level + 1])) {
                foreach (range(0, 4) as $yy) {
                    $neighbours += $this->map[$level + 1][0][$yy] == '#' ? 1 : 0;
                }
            }
        } else { // ok
            $neighbours += $this->map[$level][$x + 1][$y] == '#' ? 1 : 0;
        }

        // Then up
        if ($y == 0) { // ok
            // check level outside
            if (isset($this->map[$level - 1])) {
                $neighbours += $this->map[$level - 1][2][1] == '#' ? 1 : 0;
            }
        } elseif ($x == 2 && $y == 3) { // ok
            // check level inside
            if (isset($this->map[$level + 1])) {
                foreach (range(0, 4) as $xx) {
                    $neighbours += $this->map[$level + 1][$xx][4] == '#' ? 1 : 0;
                }
            }
        } else { // ok
            $neighbours += $this->map[$level][$x][$y - 1] == '#' ? 1 : 0;
        }

        // And lastly down
        if ($y == 4) {
            // check level outside
            if (isset($this->map[$level - 1])) {
                $neighbours += $this->map[$level - 1][2][3] == '#' ? 1 : 0;
            }
        } elseif ($x == 2 && $y == 1) {
            // check level inside
            if (isset($this->map[$level + 1])) {
                foreach (range(0, 4) as $xx) {
                    $neighbours += $this->map[$level + 1][$xx][0] == '#' ? 1 : 0;
                }
            }
        } else {
            $neighbours += $this->map[$level][$x][$y + 1] == '#' ? 1 : 0;
        }

        return $neighbours;
    }

    public function runTicks($minutes = 10)
    {
        $ticks = 0;
        do {
            $this->tick();
//            $this->printMap();
//            printf("\n______________________\n\n");
            $ticks++;
        } while ($ticks < $minutes-1);

//        printf("\n______________________\n\n");
        $this->printMap();
    }
}