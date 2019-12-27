<?php

namespace App\Day20;

use App\Day15\Point;

class PortalMaze
{
    protected $raw;
    protected $debug;
    protected $maze;
    protected $portals;
    protected $entrance;
    protected $exit;

    public function __construct($raw = [], $debug = false)
    {
        $this->raw = $raw;
        $this->maze = [];
        $this->debug = $debug;
    }

    public function parseMaze()
    {
        $portals = [];
        $characters = range('A', 'Z');

        foreach ($this->raw as $y => $row) {
            foreach (str_split($row) as $x => $value) {
//                if (!in_array($value, [' ', '#'])) {
//                    printf("%s: %s\n", new Point($x, $y), $value);
//                }

                $save = $value;

                if ($value == '.') {
                    $up = $this->peek(new Point($x, $y - 1), $this->maze);
                    if (in_array($up, $characters)) {
                        $up2 = $this->peek(new Point($x, $y - 2), $this->maze);
                        if (in_array($up2, $characters)) {
                            // Yay! We found something!
                            $portals[$up2 . $up][] = new Point($x, $y);
//                            printf("  %s ^: %s\n", new Point($x, $y - 1), $up2 . $up);
                        }
                    }
                    $left = $this->peek(new Point($x - 1, $y), $this->maze);
                    if (in_array($left, $characters)) {
                        $left2 = $this->peek(new Point($x - 2, $y), $this->maze);
                        if (in_array($left2, $characters)) {
                            // Yay! We found something!
                            $portals[$left2 . $left][] = new Point($x, $y);
//                            printf("  %s <: %s\n", new Point($x - 1, $y), $left2 . $left);
                        }
                    }
                }

                if (in_array($value, $characters)) {
//                    printf(" - %s: up %s,%s,%s left: %s,%s,%s\n", new Point($x, $y), $value, $up, $up2, $value, $left, $left2);
                    $left = $this->peek(new Point($x - 1, $y), $this->maze);
                    if (in_array($left, $characters)) {
                        $left2 = $this->peek(new Point($x - 2, $y), $this->maze);
                        if ($left2 == '.') {
                            // Yay! We found something!
                            $portals[$left . $value][] = new Point($x - 2, $y);
//                            printf("  %s: %s\n", new Point($x - 1, $y), $left . $value);
                        }
                    }

                    $up = $this->peek(new Point($x, $y - 1), $this->maze);
                    if (in_array($up, $characters)) {
                        $up2 = $this->peek(new Point($x, $y - 2), $this->maze);
                        if ($up2 == '.') {
                            // Yay! We found something!
                            $portals[$up . $value][] = new Point($x, $y - 2);
//                            printf("  %s: %s\n", new Point($x, $y - 1), $up . $value);
                        }
                    }
                }

                $this->maze[$x][$y] = $save;
            }
        }

        foreach (array_keys($portals) as $portal) {
            if ($portal == 'AA') {
                $this->entrance = $portals[$portal][0];
            } elseif ($portal == 'ZZ') {
                $this->exit = $portals[$portal][0];
            } else {
                $this->portals[$portals[$portal][0]->x][$portals[$portal][0]->y] = $portals[$portal][1];
                $this->portals[$portals[$portal][1]->x][$portals[$portal][1]->y] = $portals[$portal][0];
            }
        }
//        print_r($portals);
        if ($this->debug) {
            $this->printMap($this->maze);
//            print_r($portals);
//            print_r($this->entrance);
//            print_r($this->exit);
        }
    }

    public function shortestPathToExit(
        array &$map = [],
        Point $point = null,
        int $steps = 0,
        array &$stepMap = []
    ) {
        if (count($map) == 0) {
            $map = array_merge($this->maze);
        }
        if ($point == null) {
            $point = $this->entrance;
        }

        $stepMap[$point->x][$point->y] = $steps;

        $result = [];

        printf("%d: %s\n", $steps, $point);
        for ($direction = 1; $direction <= 5; $direction++) {
            $stepMap[$point->x][$point->y] = $steps;

            $p = $this->moveInDirection($point, $direction);
            $peek = $this->peek($p, $map);


            if ($peek == false) {
                // Wall, or space, or something
//                printf(" - %s Found a wall or space or something!\n", $p);
                continue;
            }elseif (Point::eq($point,$p)) {
                // Didn't move
//                printf(" - %s We didn't move...\n", $p);
                continue;
            } elseif (isset($stepMap[$p->x][$p->y]) && $stepMap[$p->x][$p->y] < $steps) {
                // We've been there already, but with fewer steps.
//                printf(" - %s Move forward, not backwards!\n", $p);
                continue;
            } elseif (Point::eq($p, $this->exit)) {
                printf(" - %s Next step is the exit! (%s)\n", $p, $peek);
                $stepMap[$p->x][$p->y] = $steps + 1;
                return ($steps);
                continue;
            } elseif ($peek == '.') {
                // It's either closer this way, or it's a new spot

                printf("%d: %s / %s\n", $steps, $p, $peek);

                // Find out if there's anything better that way
                $result[] = $this->shortestPathToExit($map, $p, $steps + 1, $stepMap);
            } else {
                continue;
            }
        }
        if (count($result) == 0) {
            return PHP_INT_MAX;
        }
        return min($result);
    }

    public function peek(Point $point, &$map, $portals = [])
    {
        $x = $point->x;
        $y = $point->y;

        // Manage portals when peeking
//        if (isset($this->portals[$point->x][$point->y])) {
//            $x = $this->portals[$point->x][$point->y]->x;
//            $y = $this->portals[$point->x][$point->y]->y;
//        }

        if (isset($map[$x][$y])) {
            if ($map[$x][$y] == '#') {
                // A wall. We can't get through it.
                return false;
            } elseif ($map[$x][$y] == ' ') {
                // Space. Don't even try.
                return false;
            }

            printf(" - peeked at %s: %s\n", $point, $map[$x][$y]);

            // Otherwise return the map as it is
            return $map[$x][$y];
        }
        // Here be dragons... Or a wall. Your choice.
        return false;
    }

    public function moveInDirection(Point $point, int $direction): Point
    {
        $x = $point->x;
        $y = $point->y;

        switch ($direction) {
            case 1: // north
                $y--;
                break;
            case 2: // south
                $y++;
                break;
            case 3: // west
                $x--;
                break;
            case 4: // east
                $x++;
                break;
            case 5: // use portal
                if (isset($this->portals[$point->x][$point->y])) {
                    return $this->portals[$point->x][$point->y];
                }
                break;
        }

        $newPoint = new Point($x, $y);
        // Check portals
//        if (isset($this->portals[$newPoint->x][$newPoint->y])) {
//            return $this->portals[$newPoint->x][$newPoint->y];
//        }
//        foreach (array_keys($this->portals) as $portal) {
//
//            if (in_array($portal, ['AA', 'ZZ'])) {
//                continue;
//            }
//
//            if (Point::eq($newPoint, $this->portals[$portal][0])) {
//                return $this->portals[$portal][1];
//            } elseif (Point::eq($newPoint, $this->portals[$portal][1])) {
//                return $this->portals[$portal][0];
//            }
//        }

        return $newPoint;
    }

    public function printMap($map, $stepMap = null)
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

        printf("Printing map: x: %d - %d, y: %d - %d\n", $minX, $maxX, $minY, $maxY);

        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                $print = $map[$x][$y];

//                if ($print == '.') {
//                    $print = ' ';
//                }

                if (isset($stepMap[$x][$y])) {
                    $print = '°';
                }
                printf("%s ", $print);

            }
            echo "\n";
        }

    }
}