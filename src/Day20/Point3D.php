<?php


namespace App\Day20;


use App\Day03\Distance;
use App\Day10\Point;

class Point3D
{

    public $x;
    public $y;
    public $z;

    public function __construct($x = 0, $y = 0, $z = 0)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function __toString()
    {
        return sprintf("(%d,%d,%d)", $this->x, $this->y, $this->z);
    }

    public static function Point2D(Point3D $point): Point
    {
        return new Point($point->x, $point->y);
    }

    public static function from2D(Point $point, $z): Point3D
    {
        return new Point3D($point->x, $point->y, $z);
    }

    public static function eq(Point3D $a, Point3D $b, $twoDimensional = true)
    {
        if ($twoDimensional) {
            return ($a->x == $b->x && $a->y == $b->y);
        }
        return ($a->x == $b->x && $a->y == $b->y && $a->z == $b->z);

    }

    public static function distance(Point3D $a, Point3D $b)
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

    /**
     * @return int
     */
    public function getZ(): int
    {
        return $this->z;
    }

    public function toArray(): array
    {
        return [$this->x, $this->y, $this->z];
    }
}