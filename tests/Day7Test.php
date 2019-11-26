<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day7;
use PHPUnit\Framework\TestCase;

final class Day7Test extends TestCase
{
    /**
     * @dataProvider rulesProvider
     */
    public function testRules($data, $expected)
    {
        $result = Day7::sortRules($data);

        $this->assertEquals($expected, $result);
    }

    public function rulesProvider()
    {
        return [
            [
                [
                    "Step C must be finished before step A can begin.
Step C must be finished before step F can begin.
Step A must be finished before step B can begin.
Step A must be finished before step D can begin.
Step B must be finished before step E can begin.
Step D must be finished before step E can begin.
Step F must be finished before step E can begin.",
                ],
                "CABDFE",
            ],
        ];
    }
}
