<?php

declare(strict_types=1);

namespace App\Tests\Day03;

use App\Day03;

class TobogganTrajectoryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider tobogganTrajectoryDataProvider
     * @param array<string> $rows
     * @param int $moveX
     * @param int $moveY
     * @param int $expected
     * @covers       \App\Day01\ExpenseReport
     */
    public function testTobogganTrajectory(array $rows, int $moveX, int $moveY, int $expected)
    {
        $module = new Day03\TobogganTrajectory($rows);

        $this->assertEquals($expected, $module->traverseMap($moveX, $moveY));
    }

    public function tobogganTrajectoryDataProvider()
    {
        $map = [
            '..##.......',
            '#...#...#..',
            '.#....#..#.',
            '..#.#...#.#',
            '.#...##..#.',
            '..#.##.....',
            '.#.#.#....#',
            '.#........#',
            '#.##...#...',
            '#...##....#',
            '.#..#...#.#'
        ];
        return [
            [$map, 1, 1, 2],
            [$map, 3, 1, 7],
            [$map, 5, 1, 3],
            [$map, 7, 1, 4],
            [$map, 1, 2, 2],
        ];
    }
}
