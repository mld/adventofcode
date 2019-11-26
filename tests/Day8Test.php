<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day6;
use App\Day7;
use App\Day8;
use PHPUnit\Framework\TestCase;

final class Day8Test extends TestCase
{
    /**
     * @dataProvider rootSumProvider
     */
//    public function testRootSum($data, $expected)
//    {
//        $result = Day8::sum($data);
//
//        $this->assertEquals($expected, $result);
//    }

    /**
     * @dataProvider sumProvider
     */
    public function testSum($data, $expected)
    {
        $result = Day8::sum($data);

        $this->assertEquals($expected, $result);
    }

    public function sumProvider()
    {
        return [
            [
                "2 3 0 3 10 11 12 1 1 0 1 99 2 1 1 2",
                138,
            ],
        ];
    }

    public function rootSumProvider()
    {
        return [
            [
                "2 3 0 3 10 11 12 1 1 0 1 99 2 1 1 2",
                66,
            ],
        ];
    }
}
