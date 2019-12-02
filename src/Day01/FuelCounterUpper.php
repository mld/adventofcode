<?php

namespace App\Day01;

class FuelCounterUpper
{
    protected $modules;
    public function __construct($modules = []) {
        $this->modules = $modules;
    }

    public function getFuelRequirement($simple = true) {
        $sum = 0;
        foreach ($this->modules as $mod) {
            /** @var $mod Module */
            $sum += $mod->requiredFuel($simple);
        }
        return $sum;
    }
}