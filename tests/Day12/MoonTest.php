<?php
declare(strict_types=1);

namespace App\Tests\Day12;

use App\Day12\Moon;
use PHPUnit\Framework\TestCase;

final class MoonTest extends TestCase
{
    /**
     * @dataProvider BasicMoonDataProvider
     * @param $input
     * @param $expected int
     */
    public function testMoonGravity($input, $expected)
    {
//        print_r($input);
        [$x, $y, $z] = $input[0];
        $a = new Moon($x, $y, $z);

        [$x, $y, $z] = $input[1];
        $b = new Moon($x, $y, $z);

        $a->applyGravity($b);
        $b->applyGravity($a);

//        printf('Moon $a: %s\n', $a);
//        printf('Moon $b: %s\n', $b);
        $this->assertEquals($expected[0], $a->velocity);
        $this->assertEquals($expected[1], $b->velocity);


        $a->applyVelocity();
        $b->applyVelocity();

        $this->assertEquals($expected[2], $a->position);
        $this->assertEquals($expected[3], $b->position);
    }


    public function BasicMoonDataProvider()
    {
        return [
            'test1' => [
                [
                    [3, 3, 3], // $a
                    [5, 5, 5], // $b
                ],
                [
                    [1, 1, 1], // $a:velocity round 1
                    [-1, -1, -1], // $b:velocity round 1
                    [4, 4, 4], // $a:position round 1
                    [4, 4, 4], // $b:position round 1
                ]
            ],
        ];
    }

//    /**
//     * @dataProvider AsteroidDestructionSequenceDataProvider
//     * @param $input
//     * @param $expected int
//     */
//    public function testAsteroidDestruction($input, $expected)
//    {
//        $map = new \App\Day10\AsteroidMap($input[0], false);
//        $result = $map->vaporizer($input[1]);
//        $this->assertEquals($expected, $result[0]->toArray());
//    }
//
//    public function AsteroidDestructionSequenceDataProvider()
//    {
//        $input = file_get_contents('tests/Day10/test5.txt');
//        return [
//            'test5-boom-1  ' => [[$input, 1], [11, 12]],
//            'test5-boom-2  ' => [[$input, 2], [12, 1]],
//            'test5-boom-3  ' => [[$input, 3], [12, 2]],
//            'test5-boom-10 ' => [[$input, 10], [12, 8]],
//            'test5-boom-20 ' => [[$input, 20], [16, 0]],
//            'test5-boom-50 ' => [[$input, 50], [16, 9]],
//            'test5-boom-100' => [[$input, 100], [10, 16]],
//            'test5-boom-199' => [[$input, 199], [9, 6]],
//            'test5-boom-200' => [[$input, 200], [8, 2]],
//            'test5-boom-201' => [[$input, 201], [10, 9]],
//        ];
//    }

}
