<?php
declare(strict_types=1);

namespace App\Tests\Day09;

use PHPUnit\Framework\TestCase;

final class StateMachineTest extends TestCase
{
//      * @dataProvider stateMachineV2DataProvider
    /**
     * @dataProvider stateMachineRelativeBaseDataProvider
     * @dataProvider stateMachineDay5DataProvider
     * @param $steps int[]
     * @param $expected int
     */
    public function testBoostStateMachine($steps, $expected)
    {
        $m = new \App\Day09\StateMachine($steps[0], false);
        $m->run($steps[1]);
        while (!$m->isTerminated()) {
            $m->run();
        }
        $this->assertEquals($expected, $m->getOutput());
    }

    public function stateMachineDay5DataProvider()
    {
        $oldTest = new \App\Tests\Day05\StateMachineTest();

        return $oldTest->stateMachineV2DataProvider();
    }

    public function stateMachineRelativeBaseDataProvider() {
        return [
            'test1' => [[[104,1125899906842624,99], []], 1125899906842624],
            'test2' => [[[1102,34915192,34915192,7,4,7,99,0], []], 1219070632396864],
//            'test3' => [[[109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99], []], [109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99]],
        // replace test3 with a test that compares _all_ output with the expected result
        ];
    }

}
