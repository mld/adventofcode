<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2016-12-02
 * Time: 11:25
 */

$posX = 0;
$posY = 2;
$keyPad[0] = array(2 => 1);
$keyPad[1] = array(1 => 2, 2 => 3, 3 => 4);
$keyPad[2] = array(0 => 5, 1 => 6, 2 => 7, 3 => 8, 4 => 9);
$keyPad[3] = array(1 => 'A', 2 => 'B', 3 => 'C');
$keyPad[4] = array(2 => 'D');
$code = array();

while ($line = trim(fgets(STDIN))) {
    print "> " . $line . "\n";
    $instructions = str_split($line,1);
    foreach($instructions as $next) {
        switch($next) {
            case 'U';
                if(isset($keyPad[$posY-1][$posX])) $posY--;
                break;
            case 'R':
                if(isset($keyPad[$posY][$posX+1])) $posX++;
                break;
            case 'D':
                if(isset($keyPad[$posY+1][$posX])) $posY++;
                break;
            case 'L':
                if(isset($keyPad[$posY][$posX-1])) $posX--;
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