<?php
declare(strict_types=1);

namespace App;


class Day7
{
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

//        echo "\n\n" . __FUNCTION__ . ': final: ' . "\n";
//        self::printRules($rules);
//        echo "\n";

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

            if(count($rules[$pos]) == 0) {
                // no rule for this match
                unset($rules[$pos]);
                $found[] = $pos;
                reset($rules);
                continue;
            }

            if(count(array_intersect($rules[$pos],$found)) == count($rules[$pos])) {
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
}
