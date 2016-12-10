<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-09
 * Time: 19:16
 */

$totalLength = 0;
while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    $totalLength = decompress($line);
}

print "$totalLength bytes\n";

function decompress($line)
{
    $offset = -1;
    $newLine = '';
    //$posA = 0;
    //$posB = 0;
    //$posX = 0;
    $characters = 0;

    while (($posA = strpos($line, '(', $offset)) !== false) {
        print ">>> offset: $offset, posA: $posA\n";
        if ($posA > $offset) {
            $characters += strlen(substr($line, $offset, $posA));
            print ">>> " . substr($line, $offset, $posA) . "\n";
        }
        $posX = strpos($line, 'x', $posA);
        $posB = strpos($line, ')', $posA);
        $nochars = substr($line, $posA + 1, $posX - $posA - 1);
        $repeats = substr($line, $posX + 1, $posB - $posX - 1);
        $chars = substr($line, $posB + 1, $nochars);
        for ($n = 0; $n < intval($repeats); $n++) {
            $newLine .= $chars;
        }

        if(strpos($newLine,'(') !== false) {
            $characters += decompress($newLine);

        }
        else {
            $characters += strlen($newLine);
        }
        $offset = $posB + $nochars + 1;
    }


    if ($offset > 0 && $offset < strlen($line)) {
        $characters += strlen(substr($line, $offset));
        print ">>> " . substr($line, $offset) . "\n";

    }

    if ($newLine == '') {
        return strlen($line);
    } else {
        return $characters;
    }

}

// 012345
// (1x2)a