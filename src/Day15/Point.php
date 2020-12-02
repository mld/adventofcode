<?php


namespace App\Day15;


class Point extends \App\Day10\Point
{

    public static function moveInDirection(Point $point, int $direction, $reversedYAxis = false): Point
    {
        $x = $point->x;
        $y = $point->y;

        switch ($direction) {
            case 1: // north
                $y += $reversedYAxis ? 1 : -1;
                break;
            case 2: // south
                $y += $reversedYAxis ? -1 : 1;
                break;
            case 3: // west
                $x--;
                break;
            case 4: // east
                $x++;
                break;
        }

        return new Point($x, $y);
    }
}