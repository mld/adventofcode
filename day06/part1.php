<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-06
 * Time: 08:55
 */

$chars = array();
while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    $l = str_split($line);
    foreach ($l as $k => $v) {
        if (isset($chars[$k][$v])) {
            $chars[$k][$v]++;
        } else {
            $chars[$k][$v] = 1;
        }
    }
}


$out = '';
foreach(array_keys($chars) as $k) {
    arsort($chars[$k]);
    $tmp = array_keys($chars[$k]);
    $out .= $tmp[0];
}

print "Message: $out\n";
