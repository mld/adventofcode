<?php
declare(strict_types=1);

namespace App\Tests\Day07;

use PHPUnit\Framework\TestCase;

final class StateMachineTest extends TestCase
{

    /**
     * @dataProvider stateMachineV2DataProvider
     * @param $steps int[]
     * @param $expected int
     */
    public function testAmplifierStateMachine($steps, $expected)
    {
        $m = new \App\Day07\StateMachine($steps[0], false);
        $m->run($steps[1]);
        while (!$m->isTerminated()) {
            $m->run();
        }
        $this->assertEquals($expected, $m->getOutput());
    }

    public function stateMachineV2DataProvider()
    {
        $oldTest = new \App\Tests\Day05\StateMachineTest();

        return $oldTest->stateMachineV2DataProvider();
    }

}
