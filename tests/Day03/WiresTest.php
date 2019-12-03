<?php
declare(strict_types=1);

namespace App\Tests\Day03;

use App\Day03\Wires;
use PHPUnit\Framework\TestCase;

final class WiresTest extends TestCase
{

    /**
     * @dataProvider wireDataProvider
     * @param $instructions string[]
     * @param $expected int
     */
    public function testStateMachine($instructions, $expected)
    {
        $w = new Wires($instructions[0], $instructions[1]);
        $this->assertEquals($expected[0], $w->findClosestCrossing(Wires::MANHATTAN_DISTANCE));
        $this->assertEquals($expected[1], $w->findClosestCrossing(Wires::STEPS));
    }

    public function wireDataProvider()
    {
        return [
            'test1' => [
                ["R8,U5,L5,D3", "U7,R6,D4,L4"],
                [6, 30]
            ],
            'test2' => [
                ["R75,D30,R83,U83,L12,D49,R71,U7,L72", "U62,R66,U55,R34,D71,R55,D58,R83"],
                [159, 610]
            ],
            'test3' => [
                ["R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51", "U98,R91,D20,R16,D67,R40,U7,R15,U6,R7"],
                [135, 410]
            ],
        ];
    }

}
