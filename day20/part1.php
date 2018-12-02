<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-20
 * Time: 23:48
 */

ini_set('memory_limit', '1G');

define('MIN_IP', 0);
//define('MAX_IP', 9);
define('MAX_IP', pow(2, 32));

$ip = array();

$min = MAX_IP;
$max = MIN_IP;
$lowest = 0;

while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    list($n, $m) = explode('-', $line);
    //print ">> Remove $n to $m\n";

    if($n > $lowest) {
        $min = $n;
    }
    if ($max < $n) {
        $min = $n;
        $max = $m;
    } elseif ($n > $min && $m > $max) {
        $max = $m;
    } elseif ($n < $min && $m < $max) {
        $min = $n;
    }

}

echo "Lowest blacklisted IP is $min-$max\n";
