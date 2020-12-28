<?php

namespace App\Day20;

class Grid
{
    /**
     * @param array<mixed,array> $grid
     * @return array<mixed,array>
     */
    public static function flipCols(array $grid): array
    {
        $newCropped = [];
        foreach ($grid as $row) {
            $newCropped[] = array_reverse($row);
        }
        return $newCropped;
    }

    /**
     * @param array<mixed,array> $grid
     * @param bool $left
     * @return array<mixed,array>
     */
    protected static function rotate(array $grid, bool $left): array
    {
        $new = [];
        $len = count($grid);

        for ($y = 0; $y < $len; $y++) {
            for ($x = 0; $x < $len; $x++) {
                if ($left) {
                    // rotate left
                    $new[$y][$x] = $grid[$x][$len - 1 - $y];
                } else {
                    // rotate right
                    $new[$y][$x] = $grid[$len - 1 - $x][$y];
                }
            }
        }
        return $new;
    }

    /**
     * @param array<mixed,array> $grid
     * @return array<mixed,array>
     */
    public static function rotateLeft(array $grid): array
    {
        return self::rotate($grid, true);
    }

    /**
     * @param array<mixed,array> $grid
     * @return array<mixed,array>
     */
    public static function rotateRight(array $grid): array
    {
        return self::rotate($grid, false);
    }
}
