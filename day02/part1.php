<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-02
 * Time: 11:25
 */

$posX = 1;
$posY = 1;
$keyPad[0] = array(0 => 1, 1 => 2, 2 => 3);
$keyPad[1] = array(0 => 4, 1 => 5, 2 => 6);
$keyPad[2] = array(0 => 7, 1 => 8, 2 => 9);
$code = array();
while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    $instructions = str_split($line,1);
    foreach($instructions as $next) {
        switch($next) {
            case 'U';
                $posY--;
                if($posY < 0) $posY = 0;
                break;
            case 'R':
                $posX++;
                if($posX > 2) $posX = 2;
                break;
            case 'D':
                $posY++;
                if($posY > 2) $posY = 2;
                break;
            case 'L':
                $posX--;
                if($posX < 0) $posX = 0;
                break;
            default:
                print "Invalid character $next!\n";
        }
        print ">> $next -> [$posX|$posY] => " . $keyPad[$posY][$posX] . "\n";
    }
    $code[] = $keyPad[$posY][$posX];
    print '> ' . join('',$code) . "\n";
}

print join('',$code) . "\n";