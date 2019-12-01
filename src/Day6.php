<?php
declare(strict_types=1);

namespace App;


class Day6
{
    public static function maxDistance($input, $maxDistance = 10000)
    {
        $vectors = self::parseInput($input);

        $map = [];
        $x1 = 0;
        $x2 = 0;
        $y1 = 0;
        $y2 = 0;

        // Get the map size
        foreach ($vectors as $vectorId => $vector) {
            if ($x2 < $vector[0]) {
                $x2 = $vector[0];
            }
            if ($y2 < $vector[1]) {
                $y2 = $vector[1];
            }

//            $map[$vector[0]][$vector[1]] = $vectorId;
        }
        $x2 += 2;
        $y2 += 2;

        // Figure sum of distance to all locations
        for ($y = $y1; $y < $y2; $y++) {
            for ($x = $x1; $x < $x2; $x++) {
                // Map all distances
                $location = [];
                foreach ($vectors as $vectorId => $vector) {
                    // Only set the distance
                    $location[$vectorId] = self::distance([$x, $y], $vector);
                }

                $distance = array_sum($location);

                if ($distance < $maxDistance) {
                    $map[$x][$y] = '#';
                } else {
                    $map[$x][$y] = '.';
                }
            }
        }

//        echo "\n\n\n########################################\n";
//        for ($y = $y1; $y < $y2; $y++) {
//            for ($x = $x1; $x < $x2; $x++) {
//                foreach ($vectors as $vectorId => $vector) {
//                    if ($vectorId == $map[$x][$y] && $x == $vector[0] && $y == $vector[1]) {
//                        echo '!';
//                        continue 2;
//                    }
//                }
//                echo $map[$x][$y];
//            }
//            echo "\n";
//        }
//        echo "\n########################################\n\n\n";

//        echo "### " . __FUNCTION__ . ': top: ' . $y1 . ', bottom: ' . $y2 . "\n";
//        echo "### " . __FUNCTION__ . ': left: ' . $x1 . ', right: ' . $x2 . "\n";
//        echo "### " . __FUNCTION__ . ': end' . "\n";

        $count = [];

        // Start counting size of areas
        for ($y = $y1; $y < $y2; $y++) {
            for ($x = $x1; $x < $x2; $x++) {
                $count[$map[$x][$y]] = isset($count[$map[$x][$y]]) ? $count[$map[$x][$y]] + 1 : 1;
            }
        }

//        arsort($count, SORT_NUMERIC);
//
//        return current($count);

        return $count['#'];
    }

    public static function parseInput(
        $input
    ) {
        if (!is_array($input)) {
            $input = [$input];
        }

        $out = [];
        foreach ($input as $row) {
            list($x, $y) = sscanf("$row", '%d, %d');
            if ($x > 0 && $y > 0) {
                $out[] = [$x, $y];
            }
        }
        return $out;
    }

    public static function distance($v1, $v2)
    {
        $sum = 0;
        for ($i = 0; $i < 2; $i++) {
            $sum += abs($v1[$i] - $v2[$i]);
        }
        return $sum;
    }

    public static function largestArea($input)
    {
        $vectors = self::parseInput($input);
//        echo "\n\n";

//        echo "### " . __FUNCTION__ . '::input ' . var_export($input, true) . "\n";
//        echo "### " . __FUNCTION__ . '::vectors ' . var_export($vectors, true) . "\n";

        $map = [];
        $x1 = 0;
        $x2 = 0;
        $y1 = 0;
        $y2 = 0;

        // Set up the locations
        foreach ($vectors as $vectorId => $vector) {
            if ($x2 < $vector[0]) {
                $x2 = $vector[0];
            }
            if ($y2 < $vector[1]) {
                $y2 = $vector[1];
            }

            $map[$vector[0]][$vector[1]] = $vectorId;

//            echo '(' . $vector[0] . ',' . $vector[1] . ')' . "\n";
        }
        $x2 += 2;
        $y2 += 2;

        for ($y = $y1; $y < $y2; $y++) {
            for ($x = $x1; $x < $x2; $x++) {
                if (isset($map[$x][$y])) {
                    // We don't need to calculate distances when we're already at a location
                    continue;
                }

                // Map all distances
                $location = [];
                foreach ($vectors as $vectorId => $vector) {
                    // Only set the distance
                    $location[$vectorId] = self::distance([$x, $y], $vector);
                }

                asort($location, SORT_NUMERIC);
                reset($location);
                $closest = key($location);
                next($location);
                $secondClosest = key($location);

                if ($location[$closest] == $location[$secondClosest]) {
                    $map[$x][$y] = '.';
                } else {
                    $map[$x][$y] = $closest;
                }
            }
        }

//        echo "\n\n\n########################################\n";
//        for ($y = $y1; $y < $y2; $y++) {
//            for ($x = $x1; $x < $x2; $x++) {
//                foreach ($vectors as $vectorId => $vector) {
//                    if ($vectorId == $map[$x][$y] && $x == $vector[0] && $y == $vector[1]) {
//                        echo '!';
//                        continue 2;
//                    }
//                }
//                echo $map[$x][$y];
//            }
//            echo "\n";
//        }
//        echo "\n########################################\n\n\n";

//        echo "### " . __FUNCTION__ . ': top: ' . $y1 . ', bottom: ' . $y2 . "\n";
//        echo "### " . __FUNCTION__ . ': left: ' . $x1 . ', right: ' . $x2 . "\n";
//        echo "### " . __FUNCTION__ . ': end' . "\n";

        $count = [];
        // Make sure we won't count the borders, since they are infinite
        for ($y = $y1; $y < $y2; $y++) {
            if ($map[$x1][$y] != '.') {
                $count[$map[$x1][$y]] = false;
            }
            if ($map[$x2 - 1][$y] != '.') {
                $count[$map[$x2 - 1][$y]] = false;
            }
        }

        for ($x = $x1; $x < $x2; $x++) {
            if ($map[$x][$y1] != '.') {
                $count[$map[$x][$y1]] = false;
            }
            if ($map[$x][$y2 - 1] != '.') {
                $count[$map[$x][$y2 - 1]] = false;
            }
        }

        // Start counting size of areas
        for ($y = $y1; $y < $y2; $y++) {
            for ($x = $x1; $x < $x2; $x++) {
                if (isset($count[$map[$x][$y]]) && $count[$map[$x][$y]] === false) {
                    continue;
                }
                $count[$map[$x][$y]] = isset($count[$map[$x][$y]]) ? $count[$map[$x][$y]] + 1 : 1;
            }
        }

        arsort($count, SORT_NUMERIC);

        return current($count);
    }
}
