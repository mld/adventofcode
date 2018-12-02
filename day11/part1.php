<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-11
 * Time: 15:00
 */

$steps = 0;
$floors = array();
$items = array();
/*
The first floor contains a thulium generator, a thulium-compatible microchip, a plutonium generator, and a strontium generator.
The second floor contains a plutonium-compatible microchip and a strontium-compatible microchip.
The third floor contains a promethium generator, a promethium-compatible microchip, a ruthenium generator, and a ruthenium-compatible microchip.
The fourth floor contains nothing relevant.
 */
while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    preg_match("/(\w+) floor contains (.+)/", $line, $matches);
    //print_r($matches);
    switch ($matches[1]) {
        case 'first':
            $floor = 1;
            $contains = $matches[2];
            break;
        case 'second':
            $floor = 2;
            $contains = $matches[2];
            break;
        case 'third':
            $floor = 3;
            $contains = $matches[2];
            break;
        case 'fourth':
            $floor = 4;
            $contains = $matches[2];
            break;
        default:
            $floor = false;
    }

    if ($floor !== false) {
        echo ">> Floor $floor contains $contains\n";
        preg_match_all("/a (\w+-compatible microchip|\w+ generator)+/", $contains, $matches);
        $floors[$floor] = array();
        foreach ($matches[1] as $item) {
            if (strpos($item, 'microchip') !== false) {
                $floors[$floor][] = ucfirst(substr($item, 0, 2)) . 'M';
            }
            if (strpos($item, 'generator') !== false) {
                $floors[$floor][] = ucfirst(substr($item, 0, 2)) . 'G';
            }
        }
    }
    $items = array_merge($floors[$floor], $items);
}

$items = array_unique($items);
sort($items);

echo "\n";
printFloors($floors, $items);

function printFloors($floors, $items)
{
    $floorNumbers = array_keys($floors);
    rsort($floorNumbers);
    foreach ($floorNumbers as $floor) {
        print "F$floor ";
        foreach ($items as $item) {
            if (in_array($item, $floors[$floor])) {
                print " $item";
            } else {
                print " ...";
            }
        }
        $safe = isSafe($floors[$floor]);
        print ( $safe ? ' Safe' : ' Unsafe' ) . "\n";
    }
}

function isSafe($floor) {
    foreach($floor as $item) {
        echo "> $item\n";

        if($item[2] == 'G') {
            $searchFor = substr($item,0,2) . 'M';
            if(!in_array($searchFor,$floor)) return false;
        }
    }
    return true;
}

exit;

$findLow = 17;
$findHigh = 61;
/*
$findLow = 2;
$findHigh = 5;
*/
$compareBot = false;

do {
    $found = false;
    foreach (array_keys($bots) as $bot) {
        if (isset($bots[$bot]['chips']) && count($bots[$bot]['chips']) == 2) {
            $found = true;
            $low = min($bots[$bot]['chips']);
            $high = max($bots[$bot]['chips']);

            if ($findLow == $low && $findHigh == $high) {
                $compareBot = $bot;
            }

            switch ($bots[$bot]['low']['type']) {
                case 'bot':
                    $bots[$bots[$bot]['low']['number']]['chips'][] = $low;
                    break;
                case 'output':
                    $outputs[$bots[$bot]['low']['number']]['chips'][] = $low;
                    break;
            }
            echo "Bot " . $bot . " gave chip " . $low . " to " . $bots[$bot]['low']['type'] . ' ' . $bots[$bot]['low']['number'] . "\n";

            switch ($bots[$bot]['high']['type']) {
                case 'bot':
                    $bots[$bots[$bot]['high']['number']]['chips'][] = $high;
                    break;
                case 'output':
                    $outputs[$bots[$bot]['high']['number']]['chips'][] = $high;
                    break;
            }
            echo "Bot " . $bot . " gave chip " . $high . " to " . $bots[$bot]['high']['type'] . ' ' . $bots[$bot]['high']['number'] . "\n";

            $bots[$bot]['chips'] = array();
        }
    }
} while ($found);

echo "\n=== Bots ===\n";
foreach (array_keys($bots) as $bot) {
    echo "Bot $bot has chips " . join(', ', $bots[$bot]['chips']) . "\n";
}

echo "\n=== Outputs ===\n";
foreach (array_keys($outputs) as $bot) {
    echo "Output $bot has chips " . join(', ', $outputs[$bot]['chips']) . "\n";
}

echo "\n=== CompareBot ===\n";
echo "Bot $compareBot was the one to compare $findLow and $findHigh\n";

echo "\n=== Multiplied outputs ===\n";
$out = max($outputs[0]['chips']) * max($outputs[1]['chips']) * max($outputs[2]['chips']);
echo "Product of output 0, 1 and 2: $out\n";
