<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-19
 * Time: 18:57
 */

ini_set('memory_limit','512M');

//define('ELVES',5);
define('ELVES',3014387);

$presents = array();
for($n=1;$n<=ELVES;$n++) {
    $presents[$n] = 1;
}

$turn = 1;
do {
    if(!isset($presents[$turn])) {
        if($turn >= ELVES) {
            $turn = 1;
        }
        else {
            $turn++;
        }
        continue;
    }

    $left = $turn + 1;
    $right = $turn - 1;

    while(!isset($presents[$left])) {
        if($left >= ELVES) {
            $left = 1;
        }
        else {
            $left++;
        }
    }

    $presents[$turn] += $presents[$left];
    unset($presents[$left]);

    $turn++;
} while(count($presents) > 1);

echo "> Final:";
print_r($presents);