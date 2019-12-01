<?php
declare(strict_types=1);

namespace App\Tests\Day1;

use App\Day1;
use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{

    /**
     * @dataProvider massFuelDataProvider
     */
    public function testMassFuel($mass, $expected)
    {
        $module = new Day1\Module(['mass' => $mass]);
        $this->assertEquals($expected[0], $module->requiredFuel());
        $this->assertEquals($expected[1], $module->requiredFuel(false));
    }

    public function massFuelDataProvider()
    {
        return [
            'mass 12' => [12, [2, 2]],
            'mass 14' => [14, [2, 2]],
            'mass 1969' => [1969, [654, 966]],
            'mass 100756' => [100756, [33583, 50346]],
        ];
    }

}
