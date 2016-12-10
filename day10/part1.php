<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-10
 * Time: 10:26
 */

$bots = array();
$outputs = array();

while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    if (strpos($line, "value") === 0) {
        preg_match("/value (\d+) goes to (\w+) (\d+)/", $line, $matches);
        //print_r($matches);
        switch ($matches[2]) {
            case 'bot':
                $bots[$matches[3]]['chips'][] = $matches[1];
                break;
            case 'output':
                $outputs[$matches[3]][] = $matches[1];
                break;
            default:
                echo "ERROR: ";
                print_r($matches);
                echo "\n";
        }
    } elseif (strpos($line, "bot") === 0) {
        preg_match("/bot (\d+) gives low to (\w+) (\d+) and high to (\w+) (\d+)/", $line, $matches);
        $bots[$matches[1]]['low'] = array('type' => $matches[2], 'number' => $matches[3]);
        $bots[$matches[1]]['high'] = array('type' => $matches[4], 'number' => $matches[5]);
    }
}

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
foreach(array_keys($bots) as $bot) {
    echo "Bot $bot has chips " . join(', ',$bots[$bot]['chips']) . "\n";
}

echo "\n=== Outputs ===\n";
foreach(array_keys($outputs) as $bot) {
    echo "Output $bot has chips " . join(', ',$outputs[$bot]['chips']) . "\n";
}

echo "\n=== CompareBot ===\n";
echo "Bot $compareBot was the one to compare $findLow and $findHigh\n";

echo "\n=== Multiplied outputs ===\n";
$out = max($outputs[0]['chips']) * max($outputs[1]['chips']) * max($outputs[2]['chips']);
echo "Product of output 0, 1 and 2: $out\n";
