<?php
declare(strict_types=1);

namespace App\Tests\Day06;

use App\Day06\UniversalOrbitMap;
use PHPUnit\Framework\TestCase;

final class OrbitsTest extends TestCase
{

    /**
     * @dataProvider OrbitDataProvider
     * @param $steps int[]
     * @param $expected int
     */
    public function testOrbitCounter($mapData, $expected)
    {
        $m = new UniversalOrbitMap($mapData, false);
        $this->assertEquals($expected, $m->orbitCounter());
    }

    /**
     * @dataProvider OrbitSANDataProvider
     * @param $steps int[]
     * @param $expected int
     */
    public function testOrbitPathHops($mapData, $expected)
    {
        $m = new UniversalOrbitMap($mapData, false);
        $this->assertEquals($expected, $m->shortestPath());
    }

    public function OrbitDataProvider()
    {
        return [
            'test1' => [
                [
                    'COM)B',
                    'B)C',
                    'C)D',
                    'D)E',
                    'E)F',
                    'B)G',
                    'G)H',
                    'D)I',
                    'E)J',
                    'J)K',
                    'K)L',
                ],
                42
            ],
        ];
    }

    public function OrbitSANDataProvider()
    {
        return [
            'test1' => [
                [
                    'COM)B',
                    'B)C',
                    'C)D',
                    'D)E',
                    'E)F',
                    'B)G',
                    'G)H',
                    'D)I',
                    'E)J',
                    'J)K',
                    'K)L',
                    'K)YOU',
                    'I)SAN',
                ],
                4
            ],
        ];
    }

}
