<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day6;
use App\Day7;
use PHPUnit\Framework\TestCase;

final class Day7Test extends TestCase
{
    /**
     * @dataProvider inputProvider
     */
    public function testParseCommands($data, $expected)
    {
        $result = Day7::parseInput($data);

        foreach (['step', 'before'] as $id) {
            $this->assertEquals($expected[$id], $result[$id]);
        }
    }

    /**
     * @dataProvider ruleProvider
     */
    public function testRuleSortCommands($data, $expected)
    {
        $rules = Day7::getRules($data);
        $result = Day7::sortRules($rules);

        $this->assertEquals($expected, $result);
    }

//    /**
//     * @dataProvider largestNotInfiniteProvider
//     */
//    public function testLargestArea($data, $expected)
//    {
//        $result = Day6::largestArea($data);
//
//        $this->assertEquals($expected, $result);
//    }


// CABDFE
    public function inputProvider()
    {
        return [
            [
                "Step C must be finished before step A can begin.",
                ['step' => 'C', 'before' => 'A'],
                "Step C must be finished before step F can begin.",
                ['step' => 'C', 'before' => 'F'],
                "Step A must be finished before step B can begin.",
                ['step' => 'A', 'before' => 'B'],
                "Step A must be finished before step D can begin.",
                ['step' => 'A', 'before' => 'D'],
                "Step B must be finished before step E can begin.",
                ['step' => 'B', 'before' => 'E'],
                "Step D must be finished before step E can begin.",
                ['step' => 'D', 'before' => 'E'],
                "Step F must be finished before step E can begin.",
                ['step' => 'F', 'before' => 'E'],
            ],
        ];
    }

    public function ruleProvider()
    {
        return [
            [
                [
                    'Step B must be finished before step E can begin.',
                    'Step C must be finished before step F can begin.',
                    'Step A must be finished before step D can begin.',
                    'Step C must be finished before step A can begin.',
                    'Step D must be finished before step E can begin.',
                    'Step F must be finished before step E can begin.',
                    'Step A must be finished before step B can begin.',
                ],
                'CABDFE'
            ]
        ];
    }
}
