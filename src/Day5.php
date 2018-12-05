<?php
declare(strict_types=1);

namespace App;


class Day5
{
    public static function shortestPolymer($polymer) {
        $items = str_split(trim($polymer));

        $items = array_map('strtolower', $items );
        $items = array_unique($items);

        $length = [];
        foreach($items as $char) {
            $pattern = '/' . $char . '/i';
            $subject = preg_replace($pattern, '', $polymer);

            $length[$char] = self::reactLength($subject);
        }

        arsort($length, SORT_NUMERIC);
        $n = end($length);
        return $n;
    }

    public static function reactLength($polymer) {
        return strlen(self::react($polymer));
    }

    public static function react($polymer)
    {
//        echo "\n";
//        echo "### " . $polymer . ' (' . strlen($polymer) . ")\n";


        $items = str_split(trim($polymer), 1);

        $current = reset($items);
        $currentKey = key($items);

        while ($current !== false) {
            $next = next($items);
            $nextKey = key($items);

            if ($next === false) {
                $current = false;
                continue;
            }

            if ($current == strtolower($current)) {
                // char is lower case, next should be upper
                $reactsWith = strtoupper($current);
            } else {
                // char is upper case, next should be lower
                $reactsWith = strtolower($current);
            }

            if ($next == $reactsWith) {
//                echo "### Found reaction: " . $current . $next . " (" . $currentKey . '/' . count($items) . ")\n";
                unset($items[$currentKey], $items[$nextKey]);
                $current = reset($items);
                $currentKey = key($items);
                continue;
            }

            $current = $next;
            $currentKey = $nextKey;
        }


//
//        for ($n = 0; $n < count($items) - 1; $n++) {
////            echo "##### comparing " . $items[$n] . ' with ' . $items[$n + 1] . "\n";
//            if (!isset($items[$n])) {
//                // We've managed to go outside the string
//                continue;
//            }
//
//            if ($items[$n] == strtolower($items[$n])) {
//                // char is lower case, next should be upper
//                $next = strtoupper($items[$n]);
//            } else {
//                // char is upper case, next should be lower
//                $next = strtolower($items[$n]);
//            }
//
//            if (isset($items[$n + 1]) && $items[$n + 1] == $next) {
//                echo "### Found reaction: " . $items[$n] . $items[$n + 1] . "\n";
//                unset($items[$n], $items[$n + 1]);
//                if ($n > 0) {
//                    $n = -1;
//                }
//            }

//            echo '### ' . count($items) . ': ' . join($items) . "\n";
//        }

//        echo "### " . count($items) . " $polymer reacted into " . join('', $items) . "\n";

        return join('', $items);
    }
}
