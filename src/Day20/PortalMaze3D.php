<?php

namespace App\Day20;

use App\Day10\Point;

class PortalMaze3D
{
    protected $raw;
    protected $debug;
    protected $maze;
    protected $portals;
    protected $moves;
    protected $entrance;
    protected $exit;
    protected $portalLevel;
    protected $portalNames;
    protected $portalCoordinates;


    public function __construct($raw = [], $debug = false)
    {
        $this->raw = $raw;
        $this->maze = [];
        $this->debug = $debug;
        $this->portals = [];
        $this->moves = [];
        $this->portalLevel = [];
        $this->portalNames = [];
        $this->portalCoordinates = [];
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

        $minY = PHP_INT_MAX;
        $maxY = PHP_INT_MIN;
        $minX = min(array_keys($this->maze));
        $maxX = max(array_keys($this->maze));
        foreach (array_keys($this->maze) as $x) {
            if ($minY > min(array_keys($this->maze[$x]))) {
                $minY = min(array_keys($this->maze[$x]));
            }
            if ($maxY < max(array_keys($this->maze[$x]))) {
                $maxY = max(array_keys($this->maze[$x]));
            }
        }

        printf("x min %d, max %d. y min %d, max %d\n", $minX, $maxX, $minY, $maxY);

        foreach (array_keys($portals) as $portal) {
            $p0 = $portals[$portal][0];
            if ($portal == 'AA') {
                $this->entrance = $p0;
                $this->portalCoordinates[$portal] = new Point($p0->x, $p0->y);
                $this->portalLevel[$portal] = 0;
            } elseif ($portal == 'ZZ') {
                $this->exit = $p0;
                $this->portalCoordinates[$portal] = new Point($p0->x, $p0->y);
                $this->portalLevel[$portal] = 0;
            } else {
                $p1 = $portals[$portal][1];

                $this->portals[$p0->x][$p0->y] = $p1;
                $this->portals[$p1->x][$p1->y] = $p0;

                $this->portalLevel[$portal . ' (inner)'] = +1;
                $this->portalLevel[$portal . ' (outer)'] = -1;

                // also set inner/outer portal status
                if (
                    $p0->x == $minX + 2 ||
                    $p0->x == $maxX - 2 ||
                    $p0->y == $minY + 2 ||
                    $p0->y == $maxY - 2
                ) {
                    // First point is the outer portal
                    $this->portalLevel[$p0->x][$p0->y] = -1;
                    $this->portalLevel[$p1->x][$p1->y] = +1;
                    $this->portalNames[$p0->x][$p0->y] = $portal . ' (outer)';
                    $this->portalNames[$p1->x][$p1->y] = $portal . ' (inner)';

                    $this->portalCoordinates[$portal . ' (outer)'] = new Point($p0->x, $p0->y);
                    $this->portalCoordinates[$portal . ' (inner)'] = new Point($p1->x, $p1->y);
                    $this->moves[$portal . ' (inner)'][$portal . ' (outer)'] = 1;
                    $this->moves[$portal . ' (outer)'][$portal . ' (inner)'] = 1;

                } else {
                    // Second point is the outer portal

                    $this->portalLevel[$p1->x][$p1->y] = -1;
                    $this->portalLevel[$p0->x][$p0->y] = +1;

                    $this->portalNames[$p0->x][$p0->y] = $portal . ' (inner)';
                    $this->portalNames[$p1->x][$p1->y] = $portal . ' (outer)';
                    $this->portalCoordinates[$portal . ' (inner)'] = new Point($p0->x, $p0->y);
                    $this->portalCoordinates[$portal . ' (outer)'] = new Point($p1->x, $p1->y);
                    $this->moves[$portal . ' (inner)'][$portal . ' (outer)'] = 1;
                    $this->moves[$portal . ' (outer)'][$portal . ' (inner)'] = 1;
                }
            }
        }

        foreach (array_keys($this->portalCoordinates) as $start) {
            foreach (array_keys($this->portalCoordinates) as $destination) {
                if ($start == $destination) {
                    // Well... Not many steps if the goal is to stay...
                    continue;
                }
                if (isset($this->moves[$start][$destination])) {
                    // only calculate a route once
                    continue;
                }

                $steps = $this->shortestPath($this->portalCoordinates[$start], $this->portalCoordinates[$destination]);
                if ($steps < PHP_INT_MAX) {
                    // Save for both directions
                    $this->moves[$start][$destination] = $steps;
                    $this->moves[$destination][$start] = $steps;

                    printf(
                        "Finding best path between %s %s and %s %s: %d steps\n",
                        $start,
                        $this->portalCoordinates[$start],
                        $destination,
                        $this->portalCoordinates[$destination],
                        $steps
                    );
                }
            }
        }

//        print_r($this->moves);
//        print_r($portals);
//        if ($this->debug) {
//            $this->printMap($this->maze);
//            print_r($portals);
//            print_r($this->entrance);
//            print_r($this->exit);
//        }
    }

    public function shortestPath(
        Point $start,
        Point $destination,
        int $steps = 1,
        array &$stepMap = []
    ) {

        $stepMap[$start->x][$start->y] = $steps;

        $result = [];

        for ($direction = 1; $direction <= 4; $direction++) {
            $stepMap[$start->x][$start->y] = $steps;

            // Calculate next move:
            $x = $start->x;
            $y = $start->y;

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
            }
            $point = new Point($x, $y);

            // See if there's anything interesting at that point of the maze
            $peek = $this->peek($point, $this->maze);

            if ($peek == false) {
                // Wall, or space, or something
                continue;
            } elseif (Point::eq($start, $point)) {
                // Didn't move
                continue;
            } elseif (isset($stepMap[$point->x][$point->y]) && $stepMap[$point->x][$point->y] < $steps) {
                // We've been there already, but with fewer steps.
                continue;
            } elseif (Point::eq($point, $destination)) {
                // We're there!
                $stepMap[$point->x][$point->y] = $steps + 1;
                return ($steps);
                continue;
            } elseif ($peek == '.') {
                // It's either closer this way, or it's a new spot, so...
                // Find out if there's anything better that way
                $result[] = $this->shortestPath($point, $destination, $steps + 1, $stepMap);
            } else {
                continue;
            }
        }
        if (count($result) == 0) {
            return PHP_INT_MAX;
        }
        return min($result);
    }

    public function portalPath2d(
        $start = 'AA',
        $destination = 'ZZ',
        int $steps = 0,
        array &$stepMap = []
    ) {
        if ($start == $destination) {
            return $steps;
        }

        if (isset($stepMap[$start]) && $stepMap[$start] < $steps) {
            return PHP_INT_MAX;
        }

        if (count($stepMap) == 0) {
            $stepMap[$start] = 0;
        }

//        printf("%d: Finding a path from %s to %s\n", $steps, $start, $destination);

        $result = [];

        $startXY = $this->portalCoordinates[$start];
        $destinationXY = $this->portalCoordinates[$destination];

//        printf("%d: %s\n", $steps, $point);
//        print_r($this->moves[$start]);
        foreach (array_keys($this->moves[$start]) as $next) {
            $nextXY = $this->portalCoordinates[$next];
            $nextSteps = $this->moves[$start][$next];

//            printf(" - Checking %s %s to %s %s (steps: %d+%d)\n", $start, $startXY, $next, $nextXY, $steps,
//                $nextSteps);


            if (Point::eq($startXY, $nextXY)) {
                // We didn't move, next!
//                printf("No move done\n");
                continue;
            } elseif (isset($stepMap[$next]) && $stepMap[$next] < ($steps + $nextSteps)) {
                // We've been there already, but with fewer steps.
//                printf("   - %s/%s Been here already, back then we only had to walk %d steps!\n", $next, $nextXY,
//                    $stepMap[$next]);
                continue;
            } else {
                $stepMap[$next] = $steps + $nextSteps;

//                printf("   - via %s/%s (%s)\n", $next, $nextXY, $steps + $nextSteps);
                $result[] = $this->portalPath2d($next, $destination, $steps + $nextSteps, $stepMap);
            }
        }

        if (count($result) == 0) {
            return PHP_INT_MAX;
        }
        return min($result);
    }

    public function portalPath3d(
        $start = 'AA',
        $destination = 'ZZ',
        $level = 0,
        int $steps = 0,
        array &$stepMap = []
    ) {
        if ($level < 0 || $level > count(array_keys($this->portalNames)) + 1) {
            return PHP_INT_MAX;
        }
        if ($steps > 10000) {
            return PHP_INT_MAX;
        }
        printf("Steps: %d, start: %s, dest: %s, level: %d\n", $steps, $start, $destination, $level);
        if ($start == $destination) {
            if (in_array($start, ['AA', 'ZZ']) && $level !== 0) {
                return PHP_INT_MAX;
            }
            return $steps;
        }

        if (isset($stepMap[$level][$start]) && $stepMap[$level][$start] < $steps) {
            return PHP_INT_MAX;
        }

        if (count($stepMap) == 0) {
            $stepMap[$level][$start] = 0;
        }

//        printf("%d: Finding a path from %s to %s\n", $steps, $start, $destination);

        $result = [];

        $startXY = $this->portalCoordinates[$start];
        $destinationXY = $this->portalCoordinates[$destination];

//        printf("%d: %s\n", $steps, $point);
//        print_r($this->moves[$start]);
        foreach (array_keys($this->moves[$start]) as $next) {
            if (in_array($next, ['AA', 'ZZ']) && $level != 0) {
                // Moving on - AA and ZZ only exists on level 0
                continue;
            }

            $nextXY = $this->portalCoordinates[$next];
            $nextSteps = $this->moves[$start][$next];

//            printf(" - Checking %s %s to %s %s (steps: %d+%d)\n", $start, $startXY, $next, $nextXY, $steps,
//                $nextSteps);


            if (Point::eq($startXY, $nextXY)) {
                // We didn't move, next!
//                printf("No move done\n");
                continue;
            } elseif (isset($stepMap[$level + $this->portalLevel[$next]][$next]) && $stepMap[$level + $this->portalLevel[$next]][$next] < ($steps + $nextSteps)) {
                // We've been there already, but with fewer steps.
//                printf("   - %s/%s Been here already, back then we only had to walk %d steps!\n", $next, $nextXY,
//                    $stepMap[$next]);
                continue;
            } elseif (($level + $this->portalLevel[$next]) < 0) {
                // We can't go to -1 portals
                continue;
            } else {
                $stepMap[$level + $this->portalLevel[$next]][$next] = $steps + $nextSteps;

//                printf("   - via %s/%s (%s)\n", $next, $nextXY, $steps + $nextSteps);
                $result[] = $this->portalPath3d($next, $destination, $level + $this->portalLevel[$next],
                    $steps + $nextSteps, $stepMap);
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

        if (isset($map[$x][$y])) {
            if ($map[$x][$y] == '#') {
                // A wall. We can't get through it.
                return false;
            } elseif ($map[$x][$y] == ' ') {
                // Space. Don't even try.
                return false;
            }

//            printf(" - peeked at %s: %s\n", $point, $map[$x][$y]);

            // Otherwise return the map as it is
            return $map[$x][$y];
        }
        // Here be dragons... Or a wall. Your choice.
        return false;
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