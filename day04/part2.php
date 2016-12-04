<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-04
 * Time: 15:00
 */
$sum = 0;

while ($line = trim(fgets(STDIN))) {
    //print "> " . $line . "\n";
    preg_match('/(.*)-(\d+)\[(.*)\]/', $line, $matches);
    $room = $matches[1];
    $sectorid = $matches[2];
    $checksum = $matches[3];
    if ($ret = checksum($room, $checksum)) {
        $sum += $sectorid;
        print "> " . $line . "\n";
        print "> " . decrypt($room, $sectorid) . "\n";
    }
}

print "Sum $sum\n";

function decrypt($room, $sectorid)
{
    $a = ord('a');
    $z = ord('z');
    $chars = $z - $a;
    $shift = $sectorid % $chars;
    $from = '';
    $to = '';

    for ($n=0;$n<=$chars;$n++) {
        $from .= chr($n+$a);
        $to .= chr(($n + $sectorid) % ($chars+1) + $a);
    }

    return strtr($room,$from,$to);
}

function checksum($room, $checksum)
{
    $s = count_chars(str_replace('-', '', $room), 1);

    arsort($s);

    $checkstring = '';
    $counts = array();
    foreach ($s as $k => $v) {
        $counts[$v][] = $k;
    }
    foreach ($counts as $k => $v) {
        sort($v);
        foreach ($v as $char) {
            $checkstring .= chr($char);
        }
    }

    return strncmp($checksum, $checkstring, 5) == 0;
}