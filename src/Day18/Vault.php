<?php


namespace App\Day18;


use App\Day15\Point;

class Vault
{
    protected $raw;
    protected $debug;
    protected $doors;
    protected $keys;
    protected $position;
    protected $map;


    public const DIRECTIONS = [1 => 'north', 2 => 'south', 3 => 'west', 4 => 'east'];

    public function __construct($raw, $debug = false)
    {
        $this->raw = $raw;
        $this->debug = $debug;

        $this->parseRaw();
    }

    public function parseRaw()
    {
        foreach ($this->raw as $y => $row) {
            foreach (str_split(trim($row)) as $x => $value) {
                switch ($value) {
                    case '@':
                        $save = '@';
                        $this->position = new Point($x, $y);
                        break;
                    default:
                        $save = $value;

                        if (ord($value) >= ord('A') && ord($value) <= (ord('Z'))) {
                            $this->doors[$value] = new Point($x, $y);
                        } elseif (ord($value) >= ord('a') && ord($value) <= (ord('z'))) {
                            $this->keys[$value] = new Point($x, $y);
                        }
                }

                $this->map[$x][$y] = $save;
            }
        }
        $this->printMap($this->map);
    }

    public function findKeys($map = null, Point $point = null, $keys = [], int $step = 1, $stepList = [])
    {
        if ($map == null) {
            $map = $this->map;
        }
        if ($point == null) {
            $point = $this->position;
            $stepList[] = sprintf("%s", $point);
        }
        $result = [];

        // Try to move in each direction
        for ($direction = 1; $direction <= 4; $direction++) {

            $p = Point::moveInDirection($point, $direction, false);
            $peek = $this->peek($p, $map, $keys);

            if ($peek == false) {
                // We couldn't move in that direction
//                $result[$direction] = false;
                printf("%4d: Not moving from %s to %s, as there's a wall in the way.\n", $step, $point, $p);
                continue;
            }
            if (in_array(sprintf("%s", $p), $stepList)) {
                // We've already been here, stop circling around!
                printf("%4d: Not moving from %s to %s, since we've already been there.\n", $step, $point, $p);
                continue;
            }

            printf("%4d: Moving from %s to %s, seeing %s\n", $step, $point, $p, $peek);
            if (isset($this->keys[$peek]) && !in_array($peek, $keys)) {
                $stepList = []; // reset the steplist when we've found a key - we should be able to get through a new door now
                // Oh, great! We found a new key!
                array_push($keys, $peek);

                printf("Collected: %s, %d\n", join(', ', $keys), count($keys));
                printf("Total:     %s, %d\n", join(', ', array_keys($this->keys)), count($this->keys));
                if (count($keys) == count($this->keys)) {
                    $result[$direction] = $step;
                    continue;
                }
            }
            $stepList[] = sprintf("%s", $p);
            $result[$direction] = $this->findKeys($map, $p, $keys, $step + 1, $stepList);
        }

        foreach ($result as $key => $item) {
            if ($item === false) {
                unset($result[$key]);
            }
        }

        print_r($result);
        if (count($result) == 0) {
            return false;
        }
        sort($result);
        return min($result) == 0 ? false : min($result);
    }

    public function peek(Point $point, &$map, $keys = [])
    {
        $x = $point->x;
        $y = $point->y;

        if (isset($map[$x][$y])) {
            if ($map[$x][$y] == '#') {
                // A wall. We can't get through it.
                return false;
            }

            if (ord($map[$x][$y]) >= ord('A') && ord($map[$x][$y]) <= ord('Z')) {
                if (!in_array(strtolower($map[$x][$y]), $keys)) {
                    // We don't have the key yet - treat it as a wall
                    return false;
                }
                // We _do_ have the key - treat as an open space.
                return '.';
            }
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

                if ($print == '.') {
                    $print = ' ';
                }

                if (isset($stepMap[$x][$y])) {
                    $print = '.';
                }
                printf("%s ", $print);

            }
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