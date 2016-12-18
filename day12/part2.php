<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-18
 * Time: 18:18
 */

$instructions = array();
$registers = array(
    'a' => 0,
    'b' => 0,
    'c' => 1,
    'd' => 0
);

$n = 0;
while ($line = trim(fgets(STDIN))) {
    $instructions[$n++] = explode(' ', $line);
}

$n = 0;
while (isset($instructions[$n])) {
    $list = $instructions[$n];
    switch ($list[0]) {
        case 'cpy':
            // cpy x y copies x (either an integer or the value of a register) into register y.
            switch ($list[1]) {
                case 'a':
                case 'b':
                case 'c':
                case 'd':
                    $registers[$list[2]] = $registers[$list[1]];
                    break;
                default:
                    $registers[$list[2]] = $list[1];
            }
            break;
        case 'inc':
            // inc x increases the value of register x by one.
            $registers[$list[1]]++;
            break;
        case 'dec':
            // dec x decreases the value of register x by one.
            $registers[$list[1]]--;
            break;
        case 'jnz':
            // jnz x y jumps to an instruction y away (positive means forward; negative means backward), but only if x is not zero.
            switch ($list[1]) {
                case 'a':
                case 'b':
                case 'c':
                case 'd':
                    $cmpVal = $registers[$list[1]];
                    break;
                default:
                    $cmpVal = $list[1];
            }
            if ($cmpVal !== 0) {
                $n += $list[2] - 1;
            }
            break;
    }
    $n++;
}

echo "Register A contains " . $registers['a'] . "\n";
