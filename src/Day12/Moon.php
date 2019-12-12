<?php


namespace App\Day12;


class Moon
{
    public $position;
    public $initialPosition;
    public $velocity;
    public $initialVelocity;
    public const X = 0;
    public const Y = 1;
    public const Z = 2;
    public const DIMENSIONS = ['x', 'y', 'z'];

    public function __construct($x, $y, $z)
    {
        $this->position = [$x, $y, $z];
        $this->initialPosition = [$x, $y, $z];
        $this->velocity = [0, 0, 0];
        $this->initialVelocity = [0, 0, 0];
    }

    public function __toString()
    {
//        return sprintf("Moon: Pos (%d,%d,%d) Vel (%d,%d,%d), Pot %d, Kin %d, Tot %d\n",
//            $this->position[0],
//            $this->position[1],
//            $this->position[2],
//            $this->velocity[0],
//            $this->velocity[1],
//            $this->velocity[2],
//            $this->potentialEnergy(),
//            $this->kineticEnergy(),
//            $this->totalEnergy()
//        );

        return sprintf("%d,%d,%d,%d,%d,%d",
            $this->position[0],
            $this->position[1],
            $this->position[2],
            $this->velocity[0],
            $this->velocity[1],
            $this->velocity[2],
        );
    }

    public function applyGravity(Moon $b)
    {
        // To apply gravity, consider every pair of moons. On each axis (x, y, and z), the velocity of each moon changes
        // by exactly +1 or -1 to pull the moons together. For example, if Ganymede has an x position of 3, and Callisto
        // has a x position of 5, then Ganymede's x velocity changes by +1 (because 5 > 3) and Callisto's x velocity
        // changes by -1 (because 3 < 5). However, if the positions on a given axis are the same, the velocity on that
        // axis does not change for that pair of moons.

        foreach (array_keys(self::DIMENSIONS) as $dimension) {
            $this->velocity[$dimension] += $b->position[$dimension] <=> $this->position[$dimension];
        }
    }

    public function applyVelocity()
    {
        // Once all gravity has been applied, apply velocity: simply add the velocity of each moon to its own position.
        // For example, if Europa has a position of x=1, y=2, z=3 and a velocity of x=-2, y=0,z=3, then its new position
        // would be x=-1, y=2, z=6. This process does not modify the velocity of any moon.

        foreach (array_keys(self::DIMENSIONS) as $dimension) {
            $this->position[$dimension] += $this->velocity[$dimension];
        }
    }

    public function totalEnergy()
    {
        // Then, it might help to calculate the total energy in the system. The total energy for a single moon is its
        // potential energy multiplied by its kinetic energy. A moon's potential energy is the sum of the absolute
        // values of its x, y, and z position coordinates. A moon's kinetic energy is the sum of the absolute values of
        // its velocity coordinates.
        return $this->potentialEnergy() * $this->kineticEnergy();
    }

    public function potentialEnergy()
    {
        $sum = 0;
        foreach (array_keys(self::DIMENSIONS) as $dimension) {
            $sum += abs($this->position[$dimension]);
        }
        return $sum;
    }

    public function kineticEnergy()
    {
        $sum = 0;
        foreach (array_keys(self::DIMENSIONS) as $dimension) {
            $sum += abs($this->velocity[$dimension]);
        }
        return $sum;
    }

    public static function eq(Moon $a, Moon $b, $ignoreVelocity = true)
    {
        if ($ignoreVelocity) {
            foreach (array_keys(self::DIMENSIONS) as $dimension) {
                if ($a->position[$dimension] != $b->position[$dimension]) {
                    return false;
                }
            }
            return true;
        }

        foreach (array_keys(self::DIMENSIONS) as $dimension) {
            if ($a->position[$dimension] != $b->position[$dimension] || $a->velocity[$dimension] != $b->velocity[$dimension]) {
                return false;
            }
        }
        return true;
    }

    public function circular($dimension)
    {
        return ($this->position[$dimension] == $this->initialPosition[$dimension]) && ($this->velocity[$dimension] == $this->initialVelocity[$dimension]);
    }
}