<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-03
 * Time: 13:41
 */

$count = 0;
$valid = 0;

while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    preg_match('/(\d+) +(\d+) +(\d+)/', $line, $matches);
    $total = $matches[1] + $matches[2] + $matches[3];

    $max = $matches[1];
    if($matches[2] > $max) {
        $max = $matches[2];
    }
    if($matches[3] > $max) {
        $max = $matches[3];
    }

    if(($total-$max) > $max) {
        $valid++;
    }
    $count++;
}

print "Valid $valid\n";
print "Total $count\n";