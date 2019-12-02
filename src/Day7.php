<?php
declare(strict_types=1);

namespace App;


class Day7
{
    public static function printActivity($tick = 0, $workers = [], $done = [], $withHeader = true, $prepend = '')
    {
        if ($withHeader) {
            $pattern[] = 'Tick';
            for ($n = 1; $n <= count($workers); $n++) {
                $out[] = $n;
                $pattern[] = '%-1.1s';
            }
            $out[] = 'Done';
            $pattern[] = "%s\n";

            vprintf($prepend . join('  ', $pattern), $out);
        }

        $pattern = [];
        $out = [];

        $out[] = $tick;
        $pattern[] = '%4d';

        for ($n = 1; $n <= count($workers); $n++) {
            $out[] = $workers[$n - 1];
            $pattern[] = '%-1.1s';
        }

        $out[] = join('', $done);
        $pattern[] = "%s\n";

        vprintf($prepend . join('  ', $pattern), $out);
    }

    // Second   Worker 1   Worker 2   Done
    public static function parseInput($input)
    {
//        "Step C must be finished before step A can begin.",
//        ['step' => 'C', 'before' => 'A'],

        list($step, $before) = sscanf($input, 'Step %s must be finished before step %s can begin.');

        return ['step' => $step, 'before' => $before];
    }

    public static function getRules($data)
    {
        $initial = [];
        foreach ($data as $row) {
            $parsed = self::parseInput(trim($row));

            if (isset($parsed['step'], $parsed['before'])) {
                if (!isset($initial[$parsed['step']])) {
                    $initial[$parsed['step']] = [];
                }
                if (!isset($initial[$parsed['before']])) {
                    $initial[$parsed['before']] = [];
                }
                $initial[$parsed['before']][] = $parsed['step'];
            }
        }

        $rules = [];
        // deeper rules...
        foreach ($initial as $key => $keyRules) {
            $rules[$key] = $initial[$key];
        }

        return $rules;
    }

    public static function printRules($rules)
    {
        ksort($rules);
        foreach ($rules as $char => $before) {
            if (count($before) == 0) {
                echo "$char has no rules\n";
            } else {
                sort($before);
                echo "$char requires " . join(', ', $before) . "\n";
            }
        }
    }

    public static function sortRules($rules)
    {
        $found = [];
//        echo "## Before: " . join('', array_keys($rules)) . "\n";

        ksort($rules);
        reset($rules);

        do {
            $pos = key($rules);

            if (count($rules[$pos]) == 0) {
                // no rule for this match
                unset($rules[$pos]);
                $found[] = $pos;
                reset($rules);
                continue;
            }

            if (count(array_intersect($rules[$pos], $found)) == count($rules[$pos])) {
                // all rules fulfilled for this match
                unset($rules[$pos]);
                $found[] = $pos;
                reset($rules);
                continue;
            }

            next($rules);
        } while (count($rules) > 0);

//        echo "## After: " . join('', $found) . "\n";

        return join('', $found);
    }


    public static function sortRulesWithWorkers($rules, $elves = 0, $penalty = 0)
    {
        ksort($rules);

        $found = [];
        $work = [];
        $ready = [];
        $tick = 0;
        $available = 0;

        // Set up workers
        for ($n = 0; $n <= $elves; $n++) {
            $work[$n] = '.';
            $ready[$n] = 0;
            $available++;
        }

        reset($rules);

        do {
            // First see if workers has completed any work
            $workCompleted = false;
            for ($n = 0; $n <= $elves; $n++) {
                if ($ready[$n] == $tick && $work[$n] != '.') {
                    // work completed
                    unset($rules[$work[$n]]);
                    $found[] = $work[$n];
                    $work[$n] = '.';
                    $ready[$n] = 0;
                    $available++;
                    $workCompleted = true;
                }
            }

            // If any worker completed something, we need to restart from the first unmatched rule
            if ($workCompleted) {
                reset($rules);
            }

            // Fetch the current step
            $pos = key($rules);

            // See if we're either at the end of the list, or out of workers
            if ($pos == null || $available == 0) {
                // Yup, then just tick and move on to the next iteration
//                self::printActivity($tick, $work, $found, $tick == 0);

                $tick++;
                reset($rules);
                continue;
            }


            // Make rules for adding work somewhat readable
            $noRules = count($rules[$pos]) == 0;
            $allRulesFulfilled = count(array_intersect($rules[$pos], $found)) == count($rules[$pos]);
            $inProgress = in_array($pos, $work);

            if (!$inProgress && ($noRules || $allRulesFulfilled)) {
                // match either has no ruler _OR_ all rules are fulfilled
                if ($available > 0) {
                    // Find the first available worker
                    $freeWorkers = array_filter($work, function ($v) {
                        return $v == '.';
                    });

                    // Yay, we found a slacking worker
                    $n = key($freeWorkers);
                    $available--;
                    $work[$n] = $pos;
                    $ready[$n] = $tick + $penalty + ord($work[$n]) - 64;
                }
            }

            // Move the clock forward a tick if there's no available workers or we're at the end of the list
            if ($available == 0 || next($rules) === false) {
//                self::printActivity($tick, $work, $found, $tick == 0);
                $tick++;
                reset($rules);
            }
        } while (count($rules) > 0 || ($available != ($elves + 1) && count($rules) == 0));

        return $tick - 1;
    }
}
