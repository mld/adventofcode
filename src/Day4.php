<?php
declare(strict_types=1);

namespace App;


class Day4
{
    static private $currentGuard = 0;

    public static function strategy1($data)
    {
        $guards = [];

        $schedule = self::getSchedule($data);

        foreach (array_keys($schedule) as $guardId) {
            $slept = array_sum($schedule[$guardId]);
            arsort($schedule[$guardId], SORT_NUMERIC);
            $minute = key($schedule[$guardId]);
            $guards[$guardId] = [
                'slept' => $slept,
                'minute' => $minute
            ];
        }

        uasort($guards, function ($a, $b) {
            if ($a['slept'] == $b['slept']) {
                return 0;
            }
            return ($a['slept'] > $b['slept']) ? -1 : 1;
        });

        $sleepyGuard = key($guards);

        return $sleepyGuard * $guards[$sleepyGuard]['minute'];
    }

    public static function strategy2($data)
    {
        $guards = [];

        $schedule = self::getSchedule($data);

        foreach (array_keys($schedule) as $guardId) {
            arsort($schedule[$guardId], SORT_NUMERIC);
            $minute = key($schedule[$guardId]);
            $guards[$guardId] = [
                'slept' => $schedule[$guardId][$minute],
                'minute' => $minute
            ];
        }

        uasort($guards, function ($a, $b) {
            if ($a['slept'] == $b['slept']) {
                return 0;
            }
            return ($a['slept'] > $b['slept']) ? -1 : 1;
        });

        $sleepyGuard = key($guards);

        return $sleepyGuard * $guards[$sleepyGuard]['minute'];
    }

    public static function parse($data)
    {
//     '[1518-11-01 00:00] Guard #10 begins shift',
//        [
//            'id' => 10,
//            'year' => 1518,
//            'month' => 11,
//            'day' => 1,
//            'hour' => 0,
//            'minute' => 0,
//            'action' => 'begins shift'
//        ],
//        '[1518-11-01 00:05] falls asleep',
//        [
//            'id' => 10,
//            'year' => 1518,
//            'month' => 11,
//            'day' => 1,
//            'hour' => 0,
//            'minute' => 5,
//            'action' => 'falls asleep'
//        ],
//        '[1518-11-01 00:25] wakes up',
//        [
//            'id' => 10,
//            'year' => 1518,
//            'month' => 11,
//            'day' => 1,
//            'hour' => 0,
//            'minute' => 25,
//            'action' => 'wakes up'
//        ],

        list($year, $month, $day, $hour, $minute, $event) = sscanf($data, '[%4d-%2d-%2d %2d:%2d] %[^$]s');

        $id = self::$currentGuard;
        $action = $event;

        if (strpos($event, '#') !== false) {
            list($id, $action) = sscanf($event, 'Guard #%d %[^$]s');
            self::$currentGuard = $id;
        }

        return [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'event' => $event,
            'id' => $id,
            'action' => $action,
        ];
    }

    protected static function getSchedule($data)
    {
        $start = 0;
        $currentGuard = 0;
        $schedule = [];

        sort($data);
        foreach ($data as $row) {
            $parsed = self::parse(trim($row));
            switch ($parsed['action']) {
                case 'begins shift':
                    $currentGuard = $parsed['id'];
                    break;
                case 'falls asleep':
                    $start = $parsed['minute'];
                    break;
                case 'wakes up':
                    for ($n = $start; $n < $parsed['minute']; $n++) {
                        $schedule[$currentGuard][$n] = isset($schedule[$currentGuard][$n]) ? $schedule[$currentGuard][$n] + 1 : 1;
                    }
                    break;
            }
        }
        return $schedule;
    }
}
