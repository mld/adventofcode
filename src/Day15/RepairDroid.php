<?php


namespace App\Day15;


use App\Day13\Computer;

class RepairDroid
{
    protected $code;
    protected $debug;

    public const DIRECTIONS = [1 => 'north', 2 => 'south', 3 => 'west', 4 => 'east'];

    public function __construct($code = '99', $debug = false)
    {
        $this->code = $code;
        $this->debug = $debug;
    }

    public function findOxygenSystem(
        array &$map = [],
        Point $point = null,
        Computer $computer = null,
        int $steps = 1
    ) {
        if ($point == null) {
            $point = new Point(0, 0);
        }

        if ($computer == null) {
            $computer = new Computer($this->code, false, false);
        }
        if (!isset($map[0][0])) {
            $map[0][0] = 0;
        }

        $result = false;

        for ($direction = 1; $direction <= 4; $direction++) {

            $p = Point::moveInDirection($point, $direction);
            $peek = $this->peek($p, $map);

            if ($peek === true) {
                printf("%s Next step oxygen system! (%s)\n", $p, $peek);
                $result[] = $steps;
            } elseif ($peek === false || ($peek !== false && $peek !== -1)) {
                // We haven't been there before, or we might have a shorter route
                $newComputer = clone $computer;
                $newComputer->run();
                $newComputer->input($direction);
                $output = $newComputer->output;

                switch (intval($output)) {
                    case 0: // wall
                        $map[$p->x][$p->y] = -1;
                        break;

                    case 1: // we moved one step in that direction
                        if (!isset($map[$p->x][$p->y]) || $steps < $map[$p->x][$p->y]) {
                            // It's either closer this way, or it's a new spot
                            $map[$p->x][$p->y] = $steps;

                            // Find out if there's anything better that way
                            $result = $this->findOxygenSystem($map, $p, $newComputer, $steps + 1);
                        }
                        break;
                    case 2: // We moved one step in that direction and found the oxygen system
                        if (!isset($map[$p->x][$p->y])) {
                            $map[$p->x][$p->y] = true;
                        }
                        printf("### Found oxygen system at %s after %d steps ===\n", $p, $steps);
                        $result = $steps;
                        continue 2;
                        break;
                }


            }
        }
    }

    public function fillShipWithOxygen(array &$map = [], array &$filledMap = [])
    {

        $newMap = [];
        foreach (array_keys($filledMap) as $x) {
            foreach (array_keys($filledMap[$x]) as $y) {
                for ($direction = 1; $direction <= 4; $direction++) {
                    // look through adjacent squares from last converted oxygen
                    $p = Point::moveInDirection(new Point($x, $y), $direction);
                    $peek = $this->peek($p, $map);

                    if ($peek === true || $peek === false || $peek === -1) {
                        continue;
                    } elseif ($peek >= 0) {
                        $newMap[$p->x][$p->y] = true;
                    }
                }
            }
        }

        foreach (array_keys($newMap) as $x) {
            foreach (array_keys($newMap[$x]) as $y) {
                $map[$x][$y] = true;
            }
        }

        return $newMap;
    }

    public function peek(Point $point, &$map): int
    {
        $x = $point->x;
        $y = $point->y;

        if (isset($map[$x][$y])) {
            return $map[$x][$y];
        }
        return false;
    }

    public function printOxygenMap($map)
    {
        $minY = PHP_INT_MAX;
        $maxY = PHP_INT_MIN;
        $minX = min(array_keys($map));
        $maxX = max(array_keys($map));

        foreach (array_keys($map) as $x) {
            if ($minY > min(array_keys($map[$x]))) {
                $minY = min(array_keys($map[$x]));
            }
            if ($maxY < max(array_keys($map[$x]))) {
                $maxY = max(array_keys($map[$x]));
            }
        }

        printf("\nPrinting oxygen map: x: %d - %d, y: %d - %d\n", $minX, $maxX, $minY, $maxY);

        $xFormat = "%3d";
        $n = 0;
        $header = true;
        for ($y = $maxY + 2; $y >= $minY - 1; $y--) {
            if (!$header) {
                printf("%3d: ", $y);
            } else {
                printf("     ");
            }
            for ($x = $minX - 1; $x <= $maxX + 1; $x++) {
                if ($header) {
                    printf("%3d ", $x);
                    continue;
                }
                if (isset($map[$x][$y])) {
                    if ($map[$x][$y] === true) {
                        printf('%3s', 'O');
                    } elseif ($map[$x][$y] == -1) {
                        printf('%3s', '#');
                    } else {
                        printf($xFormat, $map[$x][$y]);
                    }
                    $n++;
                } else {
                    printf('%3s', ' ');
                }
                printf(" ");
            }
            $header = false;
            echo "\n";
        }
    }

    public function printMap($map, Point $point = null)
    {
        $minY = PHP_INT_MAX;
        $maxY = PHP_INT_MIN;
        $minX = min(array_keys($map));
        $maxX = max(array_keys($map));

        if (is_null($point)) {
            $point = new Point(0, 0);
        }

        foreach (array_keys($map) as $x) {
            if ($minY > min(array_keys($map[$x]))) {
                $minY = min(array_keys($map[$x]));
            }
            if ($maxY < max(array_keys($map[$x]))) {
                $maxY = max(array_keys($map[$x]));
            }
        }

        printf("Printing map: x: %d - %d, y: %d - %d\n", $minX, $maxX, $minY, $maxY);

        $xFormat = "%3d";
        $n = 0;
        $header = true;
        for ($y = $maxY + 2; $y >= $minY - 1; $y--) {
            if (!$header) {
                printf("%3d: ", $y);
            } else {
                printf("     ");
            }
            for ($x = $minX - 1; $x <= $maxX + 1; $x++) {
                if ($header) {
                    printf("%3d ", $x);
                    continue;
                }
                if (isset($map[$x][$y])) {
                    if ($map[$x][$y] === true) {
                        printf('%3s', 'X');
                    } elseif ($map[$x][$y] == -1) {
//                        if ($x == $point->x && $y == $point->y) {
//                            printf('%3s', 'O');
//                        } else {
                        printf('%3s', '#');
//                        }
                    } else {
//                        if ($x == $point->x && $y == $point->y) {
//                            printf('%3s', 'D');
//                        } else {
                        printf($xFormat, $map[$x][$y]);
//                        }
                    }
                    $n++;
                } else {
                    printf('%3s', '.');
                }
                printf(" ");
            }
            $header = false;
            echo "\n";
        }
    }

    public function oxygenStatus(&$map, $brief = false)
    {
        $oxygen = 0;
        $left = 0;
        foreach (array_keys($map) as $x) {
            foreach (array_keys($map[$x]) as $y) {
                if (isset($map[$x][$y])) {
                    if ($map[$x][$y] === true) {
                        $oxygen++;
                    } elseif ($map[$x][$y] === false) {
                        continue;
                    } elseif ($map[$x][$y] >= 0) {
                        $left++;
                    }
                }
            }
        }
        if ($brief) {
            return ($left == 0);
        }
        return ['oxygen' => $oxygen, 'vacuum' => $left];
    }

    public function run($part2 = false)
    {

        $map = [];
        $shortestPath = $this->findOxygenSystem($map, null, null, 1);
        if (!$part2) {
            return $shortestPath;
        }
        $minutes = 0;
        $map[21][12] = -1;
        $filledMap[-20][12] = true;

        while ($this->oxygenStatus($map, true) === false) {
            $filledMap = $this->fillShipWithOxygen($map, $filledMap);
            $minutes++;
//            $status = $this->oxygenStatus($map, false);
//            printf("%d minutes: o: %d, v: %d\n", $minutes, $status['oxygen'], $status['vacuum']);
        }

//        printf("It took %d minutes to fill the ship with oxygen!\n", $minutes);
//        $this->printOxygenMap($map);

        return $minutes;
    }
}