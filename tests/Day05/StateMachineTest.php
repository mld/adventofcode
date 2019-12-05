<?php
declare(strict_types=1);

namespace App\Tests\Day05;

use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{

    /**
     * @dataProvider stateMachineV2DataProvider
     * @param $steps int[]
     * @param $expected int
     */
    public function testStateMachineV2($steps, $expected)
    {
        $m = new \App\Day05\StateMachine($steps[0], false);
        $this->assertEquals($expected, $m->run($steps[1]));
    }

    public function stateMachineV2DataProvider()
    {
        return [
            'test1' => [[[3, 0, 4, 0, 99], 1], 1],
            'PosEq8true  ' => [[[3,9,8,9,10,9,4,9,99,-1,8], 8], 1],
            'PosEq8false1' => [[[3,9,8,9,10,9,4,9,99,-1,8], 7], 0],
            'PosEq8false2' => [[[3,9,8,9,10,9,4,9,99,-1,8], 9], 0],
            'PosLt81true' => [[[3,9,7,9,10,9,4,9,99,-1,8], 7], 1],
            'PosLt81false1 ' => [[[3,9,7,9,10,9,4,9,99,-1,8], 8], 0],
            'PosLt81false2 ' => [[[3,9,7,9,10,9,4,9,99,-1,8], 9], 0],
            'ImmEq81true   ' => [[[3,3,1108,-1,8,3,4,3,99], 8], 1],
            'ImmEq81false1 ' => [[[3,3,1108,-1,8,3,4,3,99], 7], 0],
            'ImmEq81false2 ' => [[[3,3,1108,-1,8,3,4,3,99], 9], 0],
            'ImmLt81true   ' => [[[3,3,1107,-1,8,3,4,3,99], 7], 1],
            'ImmLt81false1 ' => [[[3,3,1107,-1,8,3,4,3,99], 8], 0],
            'ImmLt81false2 ' => [[[3,3,1107,-1,8,3,4,3,99], 9], 0],
            'PosZeroTrue ' => [[[3,12,6,12,15,1,13,14,13,4,13,99,-1,0,1,9], 0], 0],
            'PosZeroFalse ' => [[[3,12,6,12,15,1,13,14,13,4,13,99,-1,0,1,9], 1], 1],
            'ImmZeroTrue ' => [[[3,3,1105,-1,9,1101,0,0,12,4,12,99,1], 0], 0],
            'ImmZeroFalse ' => [[[3,3,1105,-1,9,1101,0,0,12,4,12,99,1], 1], 1],
        ];
    }

}
