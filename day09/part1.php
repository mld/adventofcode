<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-09
 * Time: 18:48
 */

$totalLength = 0;
while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    $newLine = decompress($line);
    print ">> " . strlen($newLine) . ": $newLine\n";
    $totalLength += strlen($newLine);
}

print "$totalLength bytes\n";

function decompress($line)
{
    $offset = 0;
    $newLine = '';
    $posA = 0;
    $posB = 0;
    $posX = 0;

    while (($posA = strpos($line, '(', $offset)) !== false) {
        print ">>> offset: $offset, posA: $posA\n";
        if ($posA > $offset) {
            $newLine .= substr($line, $offset, $posA);
        }
        $posX = strpos($line, 'x', $posA);
        $posB = strpos($line, ')', $posA);
        $nochars = substr($line, $posA + 1, $posX - $posA - 1);
        $repeats = substr($line, $posX + 1, $posB - $posX - 1);
        $chars = substr($line, $posB + 1, $nochars);
        for ($n = 0; $n < intval($repeats); $n++) {
            $newLine .= $chars;
        }
        $offset = $posB + $nochars + 1;
    }

    if ($offset > 0 && $offset < strlen($line)) {
        $newLine .= substr($line, $offset);
    }

    if ($newLine == '') {
        return $line;
    } else {
        return $newLine;
    }

}

// 012345
// (1x2)a