<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-07
 * Time: 20:07
 */

$count = 0;
$ssl = 0;
while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    $delimiter = "[]";
    $tls = 0;
    $hypernet = 0;
    $words = 1;
    $hypernets = array();
    $supernets = array();
    $word = strtok($line, $delimiter);

    do {
        echo ">> $word : $tls : $hypernet\n";
        if ($words % 2 == 1) {
            if (abba($word)) {
                $tls++;
            }
            $supernets[] = $word;
        } else {
            if (abba($word)) {
                $hypernet++;
            }
            $hypernets[] = $word;
        }
        $words++;
        $word = strtok($delimiter);
    } while ($word !== false);

    if ($tls > 0 && $hypernet == 0) {
        $count++;
//        echo "$count\n";
    } else {
//        echo "false\n";
    }

    $abas = array();
    foreach ($supernets as $net) {
        $abas = array_merge($abas, aba($net));
    }
    foreach ($hypernets as $net) {
        if (bab($net, $abas)) {
            $ssl++;
            continue;
        }
    }
}

print "$count addresses supports TLS\n";
print "$ssl   addresses supports SSL\n";

function abba($word)
{
    for ($n = strlen($word) - 4; $n >= 0; $n--) {
//        echo '>>> [' . $n . '] ' . $word[$n] . $word[$n+1] . $word[$n+2] . $word[$n+3] . "\n";
        if ($word[$n + 3] == $word[$n] && $word[$n + 2] == $word[$n + 1] && $word[$n] !== $word[$n + 1]) {
            return true;
        }
    }
    return false;
}
function aba($word)
{
    $return = array();
    for ($n = strlen($word) - 3; $n >= 0; $n--) {
        //echo '>>> [' . $n . '] ' . $word . ': ' . $word[$n] . $word[$n+1] . $word[$n+2] . "\n";
        if ($word[$n + 2] == $word[$n] && $word[$n] !== $word[$n + 1]) {
            $return[] = $word[$n] . $word[$n + 1] . $word[$n + 2];
            //echo $word[$n] . $word[$n + 1] . $word[$n + 2] . "\n";
        }
    }
    return $return;
}
function bab($word, $abas)
{
    foreach ($abas as $aba) {
        $bab = $aba[1] . $aba[0] . $aba[1];
        if (strpos($word, $bab) !== false) {
            return true;
        }
    }
    return false;
}