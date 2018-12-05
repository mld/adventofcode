<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day3;
use App\Day4;
use App\Day5;
use PHPUnit\Framework\TestCase;

final class Day5Test extends TestCase
{
    /**
     * @dataProvider stringDataProvider
     */
    public function testReactPolymer($data, $expected)
    {
        $result = Day5::react($data);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider countDataProvider
     */
    public function testReactPolymerLength($data, $expected)
    {
        $result = Day5::reactLength($data);

        $this->assertEquals($expected, $result);
    }

    public function stringDataProvider()
    {
        return [
            [
                'dabAcCaCBAcCcaDA',
                'dabCBAcaDA',
            ],
        ];
    }

    public function countDataProvider()
    {
        return [
            [
                'dabAcCaCBAcCcaDA',
                10,
            ],
        ];
    }

    /**
     * @dataProvider countReducedDataProvider
     */
    public function testReducedReactPolymerLength($data, $expected)
    {
        $result = Day5::shortestPolymer($data);

        $this->assertEquals($expected, $result);
    }


    public function countReducedDataProvider()
    {
        return [
            [
                'dabAcCaCBAcCcaDA',
                4,
            ],
        ];
    }
}
