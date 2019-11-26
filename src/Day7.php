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

        echo "\n\n";
        $rules = [];
        // deeper rules...
        foreach ($initial as $key => $keyRules) {
//            echo "## " . __FUNCTION__ . ": expanding rules for $key\n";

//            $rules[$key] = self::expandRule($key, $initial);
            $rules[$key] = $initial[$key];
        }

        echo "\n\n" . __FUNCTION__ . ': initial: ' . "\n";
        self::printRules($initial);
        echo "\n";
        echo "\n\n" . __FUNCTION__ . ': final: ' . "\n";
        self::printRules($rules);
        echo "\n";

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

    public static function expandRule($key, $rules, $in = [], $pre = '###')
    {
        if (!is_array($rules[$key]) || count($rules[$key]) == 0) {
            return $in;
        }

//        echo $pre . " " . __FUNCTION__ . ": expanding $key: \n";

        $result = $in;
        foreach ($rules[$key] as $rule) {
            $newRules = self::expandRule($rule, $rules, $rules[$key], $pre . '##');
            $result = array_merge($newRules, $result);
        }

//        echo $pre . " " . __FUNCTION__ . ": $key rules expanded to " . join('', array_unique($result)) . "\n";
        return array_unique($result);
    }

    public static function sortRules($rules)
    {
        echo "## Before: " . join('', array_keys($rules)) . "\n";
        uksort($rules, function ($a, $b) use ($rules) {
            if (in_array($b, $rules[$a])) {
                echo "#### $a requires $b\n";
                return 1;
            }
            if (in_array($a, $rules[$b])) {
                echo "#### $b requires $a\n";
                return -1;
            }

            $cmp = strcmp($a, $b);
            if ($cmp < 0) {
                echo "#### $a goes before $b (by alphabet)\n";
            } elseif ($cmp > 0) {
                echo "#### $b goes before $a (by alphabet)\n";
            } else {
                echo "#### Undefined $cmp för $a/$b (by alphabet)\n";
            }

            return $cmp;
        });

        echo "## After: " . join('', array_keys($rules)) . "\n";

        return join('', array_keys($rules));
    }
}
