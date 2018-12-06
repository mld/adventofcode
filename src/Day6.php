<?php
declare(strict_types=1);

namespace App;


class Day6
{
    public static function distance($v1, $v2)
    {
        $sum = 0;
        for ($i = 0; $i < 2; $i++) {
            $sum += abs($v1[$i] - $v2[$i]);
        }
        return $sum;
    }

    public static function distanceMap($x1, $y1, $x2, $y2, $vectors)
    {

        $map = [];
        for ($x = $x1; $x < $x2; $x++) {
            for ($y = $y1; $y < $y2; $y++) {
                foreach ($vectors as $vector) {
                    $distance = self::distance([$x,$y],$vector);

// todo continue...

                    $map[$vector[0]][$vector[1]] = 1;
                }
            }
        }
    }

    public static function parseInput($input)
    {
//        echo "\n\n";

//        echo "### " . __FUNCTION__ . ': ' . var_export($input, true) . "\n";

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

//        echo "### " . __FUNCTION__ . ': ' . var_export($input, true) . "\n";
//        echo "### " . __FUNCTION__ . ': ' . var_export($out, true) . "\n";
//        echo "### " . __FUNCTION__ . ': end' . "\n";
        return $out;
    }

    public static function largestArea($input)
    {
        $vectors = self::parseInput($input);
        echo "\n\n";

//        echo "### " . __FUNCTION__ . '::input ' . var_export($input, true) . "\n";
//        echo "### " . __FUNCTION__ . '::vectors ' . var_export($vectors, true) . "\n";

        $map = [];
        $x1 = 0;
        $x2 = 0;
        $y1 = 0;
        $y2 = 0;

        foreach ($vectors as $vector) {
            if ($x2 < $vector[0]) {
                $x2 = $vector[0];
            }
            if ($y2 < $vector[1]) {
                $y2 = $vector[1];
            }

            $map[$vector[0]][$vector[1]] = 1;
        }
        $x2++;
        $y2++;


        echo "### " . __FUNCTION__ . ': top: ' . $y1 . ', bottom: ' . $y2 . "\n";
        echo "### " . __FUNCTION__ . ': left: ' . $x1 . ', right: ' . $x2 . "\n";


        echo "### " . __FUNCTION__ . ': end' . "\n";

        return 0;
    }
}
