<?php


namespace App\Day10;


use App\Day03\Distance;

class Point
{
    public $x;
    public $y;

    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function __toString()
    {
        return sprintf("(%d,%d)", $this->x, $this->y);
    }

    public static function eq(Point $a, Point $b)
    {
        return ($a->x == $b->x && $a->y == $b->y);
    }

    public static function distance(Point $a, Point $b)
    {
        return Distance::manhattan($a->toArray(), $b->toArray());
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    public function toArray(): array
    {
        return [$this->x, $this->y];
    }

    public static function angle(Point $a, Point $b): float
    {
        $angle = rad2deg(atan2($b->getY() - $a->getY(), $b->getX() - $a->getX()));
        return $angle;
    }
}