<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-09
 * Time: 19:16
 */

$debug = false;
$totalLength = 0;
while ($line = trim(fgets(STDIN))) {
    if($debug) print "> " . $line . "\n";
    $length = decompress($line,1,$debug);
    $totalLength += $length;
    if($debug) print "> length: " . $length . "\n\n";
}
if($debug) echo "\n";
print "$totalLength bytes\n";

function decompress($line,$depth = 1,$debug=false)
{
    if($debug) print str_repeat('>',$depth + 1) . " $line\n";
    $sum = 0;
    if(($posA = strpos($line, '(')) !== false) {
        if($debug) print str_repeat('>',$depth + 2) . " pos of (: $posA\n";

        if ($posA > 0) {
            $sum += $posA;
        }

        $posX = strpos($line, 'x', $posA);
        $posB = strpos($line, ')', $posA);
        $nochars = intval(substr($line, $posA + 1, $posX - $posA - 1));
        $repeats = intval(substr($line, $posX + 1, $posB - $posX - 1));
        $chars = substr($line, $posB + 1, $nochars);
        if($debug) echo str_repeat('>',$depth + 1) . " next: $chars\n";
        $sum += $repeats * decompress($chars,$depth+1,$debug);
        if($debug) echo str_repeat('>',$depth + 1) . " ($nochars".'x'."$repeats) sum: $sum\n";
        if(strlen($line) > $posB + 1 + $nochars) {
            $sum += decompress(substr($line,$posB+1+$nochars),$depth+1,$debug);
        }
    }
    else {
        return strlen($line);
    }
    return $sum;

}
