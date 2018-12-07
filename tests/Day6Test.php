<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day6;
use PHPUnit\Framework\TestCase;

final class Day6Test extends TestCase
{
    /**
     * @dataProvider distanceProvider
     */
    public function testDistance($data, $expected)
    {
        $result = Day6::distance($data[0], $data[1]);

        $this->assertEquals($expected, $result);
    }


    public function testMaxDistance()
    {
        $data = [
            "1, 1",
            "1, 6",
            "8, 3",
            "3, 4",
            "5, 5",
            "8, 9",
        ];
        $maxDistance = 32;
        $expected = 16;

        $result = Day6::maxDistance($data, $maxDistance);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider largestNotInfiniteProvider
     */
    public function testLargestArea($data, $expected)
    {
        $result = Day6::largestArea($data);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider inputProvider
     */
//    public function testInput($data, $expected)
//    {
//        echo "\n\n";
//        echo "### " . __FUNCTION__ . '::input ' . var_export($data, true) . "\n";
//
//
//        $result = Day6::parseInput($data);
//        echo "### " . __FUNCTION__ . '::result ' . var_export($result, true) . "\n";
//
//        $this->assertEquals($expected[0], $result[0]);
//        $this->assertEquals($expected[1], $result[1]);
//    }

    public function distanceProvider()
    {
        return [
            [
                [[1, 1], [1, 6],],
                5,
            ],
            [
                [[8, 3], [3, 4],],
                6,
            ],
            [
                [[5, 5], [8, 9],],
                7,
            ],
            [
                [[1, 6], [8, 9],],
                10,
            ],
        ];
    }


//    public function inputProvider()
//    {
//        return [
//            [
//                "1, 1",
//                "1, 6",
//                "8, 3",
//                "3, 4",
//                "5, 5",
//                "8, 9",
//            ],
//            [
//                [1, 1],
//                [1, 6],
//                [8, 3],
//                [3, 4],
//                [5, 5],
//                [8, 9],
//            ]
//
//        ];
//    }

    public function largestNotInfiniteProvider()
    {
        return [
            [
                [
                    "1, 1",
                    "1, 6",
                    "8, 3",
                    "3, 4",
                    "5, 5",
                    "8, 9",
                ],
                17,
            ],
        ];
    }

    public function maxDistanceProvider()
    {
        return [
            [
                [
                    'data' => [
                        "1, 1",
                        "1, 6",
                        "8, 3",
                        "3, 4",
                        "5, 5",
                        "8, 9",
                    ],
                    'maxDistance' => 32,
                ],
                16,
            ],
        ];
    }
}
