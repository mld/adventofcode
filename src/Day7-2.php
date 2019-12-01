<?php
declare(strict_types=1);

namespace App;


class Day7
{
    public static function getRules($data, $expanded = false, $debug = false)
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
                $initial[$parsed['step']][] = $parsed['before'];
            }
        }

        $rules = $initial;

        if ($expanded) {
            $rules = [];
            foreach ($initial as $key => $keyRules) {
                $rules[$key] = self::expandRule($key, $initial);
            }
        }

        if ($debug) {
            echo "\n\n" . __FUNCTION__ . ':' . "\n";
            self::printRules($rules);
            echo "\n";
        }

        return $rules;
    }

    public static function parseInput($input)
    {
//        "Step C must be finished before step A can begin.",
//        ['step' => 'C', 'before' => 'A'],

        list($step, $before) = sscanf($input, 'Step %s must be finished before step %s can begin.');

        return ['step' => $step, 'before' => $before];
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

    public static function printRules($rules)
    {
        ksort($rules);
        foreach ($rules as $char => $before) {
            if (count($before) == 0) {
                echo "$char has no rules\n";
            } else {
                sort($before);
                echo "$char comes before " . join(', ', $before) . "\n";
            }
        }
    }

    public static function sortRules($rules)
    {
        echo __FUNCTION__ . ": Starting with " . join('', array_keys($rules)) . "\n";
        $result = '';

        $items = array_keys($rules);
        while (($item = array_shift($items))) {
            $first = true;
            foreach ($rules as $ruleKey => $before) {
                if ($item == $ruleKey) {
                    continue;
                }
                switch (self::compare($item, $ruleKey, $rules)) {
                    case -1:
                        // $item goes before rulekey
                        break;
                    case 1:
                        // $item goes _after_ ruleKey
                        $first = false;
                        break;
                    case 0:
                        // $item and rulekey are the same
                        break;
                }

            }
            if ($first) {
                echo "## $item is the first character of the remaining ones!\n";
                $result .= $item;
//                unset($rules[$item]);
            } else {
                echo "## $item to end of queue :(\n";
                array_push($items, $item);
            }

            echo "## Current result: " . $result . "\n";

        }

////        sort($items);
////        ksort($rules);
//
//        while (($item = array_shift($items))) {
//            $first = true;
//
//            $toTest = $items;
//            $tested = [];
//
//            foreach ($toTest as $testRule) {
//
//
//            }
//            foreach ($rules as $ruleKey => $before) {
//                if ($item == $ruleKey) {
//                    continue;
//                }
//                switch (self::compare($item, $ruleKey, $rules)) {
//                    case -1:
//                        // $item goes before rulekey
//                        break;
//                    case 1:
//                        // $item goes _after_ ruleKey
//                        $first = false;
//                        break;
//                    case 0:
//                        // $item and rulekey are the same
//                        break;
//                }
//
//            }
//            if ($first) {
//                echo "## $item is the first character of the remaining ones!\n";
//                $result .= $item;
////                unset($rules[$item]);
//            } else {
//                echo "## $item to end of queue :(\n";
//                array_push($items, $item);
//            }
//        }
////        uksort($rules, function ($a, $b) use ($rules) {
////            if (in_array($b, $rules[$a])) {
////                echo "#### $a goes before $b\n";
////                return -1;
////            }
////            if (in_array($a, $rules[$b])) {
////                echo "#### $b goes before $a\n";
////                return 1;
////            }
////
////            $cmp = strcmp($a, $b);
////            if ($cmp < 0) {
////                echo "#### $a goes before $b (by alphabet)\n";
////            } elseif ($cmp > 0) {
////                echo "#### $b goes before $a (by alphabet)\n";
////            } else {
////                echo "#### Undefined $cmp för $a/$b (by alphabet)\n";
////            }
////
////            return $cmp;
////        });
//
//        echo "## After: " . $result . "\n";
//
        echo __FUNCTION__ . ": Returns " . $result . "\n";

        return $result;
    }

    public static function compare($a, $b, $rules, $pre = '####')
    {
        if (in_array($b, $rules[$a])) {
            echo "$pre $a goes before $b\n";
            return -1;
        }
        if (in_array($a, $rules[$b])) {
            echo "$pre $b goes before $a\n";
            return 1;
        }

        if (count($rules[$b]) > 0) {
            echo "$pre $b has dependencies, deep checking\n";

            foreach ($rules[$b] as $c) {
                $cmp = self::compare($a, $c, $rules, $pre . '##');
                if ($cmp == 1) {
                    echo "$pre $c goes before $a (deep check)\n";
                    return 1;
                } elseif ($cmp == -1) {
                    echo "$pre $a goes before $c (deep check)\n";
                    return -1;
                } else {

                }
            }
        }

        $cmp = strcmp($a, $b);
        if ($cmp < 0) {
            echo "$pre $a goes before $b (by alphabet)\n";
            $cmp = -1;
        } elseif ($cmp > 0) {
            echo "$pre $b goes before $a (by alphabet)\n";
            $cmp = 1;
        } else {
            echo "$pre Undefined $cmp för $a/$b (by alphabet)\n";
            $cmp = 0;
        }

        return $cmp;
    }
}
