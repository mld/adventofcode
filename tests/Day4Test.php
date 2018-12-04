<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day3;
use App\Day4;
use PHPUnit\Framework\TestCase;

final class Day4Test extends TestCase
{
    /**
     * @dataProvider shiftDataProvider
     */
    public function testParseCommands($data, $expected)
    {
        $result = Day4::parse($data);

        foreach (['year', 'month', 'day', 'hour', 'minute', 'id', 'action'] as $id) {
            $this->assertEquals($expected[$id], $result[$id]);
        }
    }

    /**
     * @dataProvider strategy1DataProvider
     */
    public function testStrategy1($data, $expected)
    {
        $overlap = Day4::strategy1($data);
        $this->assertEquals($expected, $overlap);
    }

    /**
     * @dataProvider strategy2DataProvider
     */
    public function testStrategy2($data, $expected)
    {
        $overlap = Day4::strategy2($data);
        $this->assertEquals($expected, $overlap);
    }

    public function shiftDataProvider()
    {
        return [
            [
                '[1518-11-01 00:00] Guard #10 begins shift',
                [
                    'id' => 10,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 1,
                    'hour' => 0,
                    'minute' => 0,
                    'action' => 'begins shift'
                ],
                '[1518-11-01 00:05] falls asleep',
                [
                    'id' => 10,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 1,
                    'hour' => 0,
                    'minute' => 5,
                    'action' => 'falls asleep'
                ],
                '[1518-11-01 00:25] wakes up',
                [
                    'id' => 10,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 1,
                    'hour' => 0,
                    'minute' => 25,
                    'action' => 'wakes up'
                ],
                '[1518-11-01 00:30] falls asleep',
                [
                    'id' => 10,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 1,
                    'hour' => 0,
                    'minute' => 30,
                    'action' => 'falls asleep'
                ],
                '[1518-11-01 00:55] wakes up',
                [
                    'id' => 10,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 1,
                    'hour' => 0,
                    'minute' => 55,
                    'action' => 'wakes up'
                ],

                '[1518-11-01 23:58] Guard #99 begins shift',
                [
                    'id' => 99,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 1,
                    'hour' => 23,
                    'minute' => 58,
                    'action' => 'begins shift'
                ],
                '[1518-11-02 00:40] falls asleep',
                [
                    'id' => 99,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 2,
                    'hour' => 0,
                    'minute' => 40,
                    'action' => 'falls asleep'
                ],
                '[1518-11-02 00:50] wakes up',
                [
                    'id' => 99,
                    'year' => 1518,
                    'month' => 11,
                    'day' => 2,
                    'hour' => 0,
                    'minute' => 50,
                    'action' => 'wakes up'
                ],
            ],
        ];
    }

    public function strategy1DataProvider()
    {
        return [
            [
                [
                    '[1518-11-01 00:00] Guard #10 begins shift',
                    '[1518-11-01 00:05] falls asleep',
                    '[1518-11-01 00:25] wakes up',
                    '[1518-11-01 00:30] falls asleep',
                    '[1518-11-01 00:55] wakes up',
                    '[1518-11-01 23:58] Guard #99 begins shift',
                    '[1518-11-02 00:40] falls asleep',
                    '[1518-11-02 00:50] wakes up',
                    '[1518-11-03 00:05] Guard #10 begins shift',
                    '[1518-11-03 00:24] falls asleep',
                    '[1518-11-03 00:29] wakes up',
                    '[1518-11-04 00:02] Guard #99 begins shift',
                    '[1518-11-04 00:36] falls asleep',
                    '[1518-11-04 00:46] wakes up',
                    '[1518-11-05 00:03] Guard #99 begins shift',
                    '[1518-11-05 00:45] falls asleep',
                    '[1518-11-05 00:55] wakes up',
                ],
                240
            ]
        ];
    }

    public function strategy2DataProvider()
    {
        return [
            [
                [
                    '[1518-11-01 00:00] Guard #10 begins shift',
                    '[1518-11-01 00:05] falls asleep',
                    '[1518-11-01 00:25] wakes up',
                    '[1518-11-01 00:30] falls asleep',
                    '[1518-11-01 00:55] wakes up',
                    '[1518-11-01 23:58] Guard #99 begins shift',
                    '[1518-11-02 00:40] falls asleep',
                    '[1518-11-02 00:50] wakes up',
                    '[1518-11-03 00:05] Guard #10 begins shift',
                    '[1518-11-03 00:24] falls asleep',
                    '[1518-11-03 00:29] wakes up',
                    '[1518-11-04 00:02] Guard #99 begins shift',
                    '[1518-11-04 00:36] falls asleep',
                    '[1518-11-04 00:46] wakes up',
                    '[1518-11-05 00:03] Guard #99 begins shift',
                    '[1518-11-05 00:45] falls asleep',
                    '[1518-11-05 00:55] wakes up',
                ],
                4455
            ]
        ];
    }

}
