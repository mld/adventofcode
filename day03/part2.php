<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-03
 * Time: 14:04
 */

$count = 0;
$valid = 0;
$linen = 0;
$tri = array();

while ($line = trim(fgets(STDIN))) {
    print "$linen > " . $line . "\n";
    preg_match('/(\d+) +(\d+) +(\d+)/', $line, $matches);

    $tri[$linen][0] = $matches[1];
    $tri[$linen][1] = $matches[2];
    $tri[$linen][2] = $matches[3];
    $linen++;

    if ($linen == 3) {
        for ($n = 0; $n < 3; $n++) {
            $total = $tri[0][$n] + $tri[1][$n] + $tri[2][$n];

            $max = $tri[0][$n];
            if ($tri[1][$n] > $max) {
                $max = $tri[1][$n];
            }
            if ($tri[2][$n] > $max) {
                $max = $tri[2][$n];
            }

            if (($total - $max) > $max) {
                $valid++;
            }
            $count++;
        }
        $tri = array();
        $linen = 0;
    }
}

print "Valid $valid\n";
print "Total $count\n";