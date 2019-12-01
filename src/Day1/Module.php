<?php

namespace App\Day1;

class Module
{
    protected $properties;

    public function __construct($properties = [])
    {
        $this->properties = $properties;
    }

    public function requiredFuel($simple = true)
    {
        if($simple) {
            if (isset($this->properties['mass'])) {
                // take its mass, divide by three, round down, and subtract 2.
                return intval($this->properties['mass'] / 3) - 2;
            }
            return 0;
        }
        // $simple == false - include fuel for the extra weight of fuel too
        return $this->calculateFuelForMass($this->properties['mass']);
    }

    public function calculateFuelForMass($mass)
    {
        $fuel = intval($mass / 3) - 2;
        if ($fuel <= 0) {
            return 0;
        }
        return $fuel + $this->calculateFuelForMass($fuel);
    }
}