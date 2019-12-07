<?php
declare(strict_types=1);

namespace App\Tests\Day01;

use App\Day01;
use PHPUnit\Framework\TestCase;

final class FuelCounterUpperTest extends TestCase
{

    /**
     * @dataProvider massFuelDataProviderComplex
     */
    public function testFuelCounterSimple($moduleMass, $expected)
    {
        if (!is_array($moduleMass)) {
            $moduleMass = [$moduleMass];
        }

        $modules = [];

        foreach ($moduleMass as $mass) {
            $modules[] = new Day01\Module(['mass' => $mass]);
        }

        $fuelCounterUpper = new Day01\FuelCounterUpper($modules);
        $sumSimple = $fuelCounterUpper->getFuelRequirement(true);
        $sumComplex = $fuelCounterUpper->getFuelRequirement(false);
        $this->assertEquals($expected[0], $sumSimple);
        $this->assertEquals($expected[1], $sumComplex);
    }

    public function massFuelDataProviderComplex()
    {
        return [
            'modules' => [
                [12, 14, 1969, 100756],
                [34241, 51316],
            ]
        ];
    }
}
