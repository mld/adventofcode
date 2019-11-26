<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day6;
use App\Day7;
use App\Day8;
use PHPUnit\Framework\TestCase;

//final class Day9Test extends TestCase
//{
//    /**
//     * @dataProvider rootSumProvider
//     */
//    public function testRootSum($data, $expected)
//    {
//        $result = Day8::sum($data);
//
//        $this->assertEquals($expected, $result);
//    }
//
//    /**
//     * @dataProvider sumProvider
//     */
//    public function testSum($data, $expected)
//    {
//        $result = Day8::sum($data);
//
//        $this->assertEquals($expected, $result);
//    }
//
//    public function inputProvider()
//    {
//        return [
//            [
//                "9 players; last marble is worth 25 points",
//                ['players' => 9, 'points' => 25],
//                "10 players; last marble is worth 1618 points",
//                ['players' => 9, 'points' => 25],
//                "13 players; last marble is worth 7999 points",
//                "17 players; last marble is worth 1104 points",
//                "21 players; last marble is worth 6111 points",
//                "30 players; last marble is worth 5807 points",
//            ],
//        ];
//    }
//
//    public function gameProvider()
//    {
//        return [
//            [
//                "9 players; last marble is worth 25 points",
//                32,
//                "10 players; last marble is worth 1618 points",
//                8317,
//                "13 players; last marble is worth 7999 points",
//                146373,
//                "17 players; last marble is worth 1104 points",
//                2764,
//                "21 players; last marble is worth 6111 points",
//                54718,
//                "30 players; last marble is worth 5807 points",
//                37305,
//            ],
//        ];
//    }
//
//    public function rootSumProvider()
//    {
//        return [
//            [
//                "2 3 0 3 10 11 12 1 1 0 1 99 2 1 1 2",
//                66,
//            ],
//        ];
//    }
//}
