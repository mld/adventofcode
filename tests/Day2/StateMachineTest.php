<?php
declare(strict_types=1);

namespace App\Tests\Day2;

use App\Day2\StateMachine;
use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{

    /**
     * @dataProvider stateMachineDataProvider
     * @param $steps int[]
     * @param $expected int
     */
    public function testStateMachine($steps, $expected)
    {
        $m = new StateMachine($steps);
        $this->assertEquals($expected, $m->run());
    }

    public function stateMachineDataProvider()
    {
        return [
            '1,0,0,0,99' => [[1, 0, 0, 0, 99], 2],
            '2,3,0,3,99' => [[2, 3, 0, 3, 99], 2],
            '2,4,4,5,99,0' => [[2, 4, 4, 5, 99, 0], 2],
            '1,1,1,4,99,5,6,0,99' => [[1, 1, 1, 4, 99, 5, 6, 0, 99], 30],
        ];
        /*
1,0,0,0,99 becomes 2,0,0,0,99 (1 + 1 = 2).
2,3,0,3,99 becomes 2,3,0,6,99 (3 * 2 = 6).
2,4,4,5,99,0 becomes 2,4,4,5,99,9801 (99 * 99 = 9801).
1,1,1,4,99,5,6,0,99 becomes 30,1,1,4,2,5,6,0,99. 
         */
    }

}
