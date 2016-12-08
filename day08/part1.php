<?php

define('ScreenX',50);
define('ScreenY',6);

/*
define('ScreenX',7);
define('ScreenY',3);
*/

// Init screen
$screen = array();
for($x=0;$x<ScreenX;$x++) {
	for($y=0;$y<ScreenY;$y++) {
		$screen[$x][$y] = '.';
	}
}

/*
$screen = rect($screen,3,2);
printScreen($screen);
$screen = flipColumn($screen,1,1);
printScreen($screen);
$screen = flipRow($screen,0,4);
printScreen($screen);
$screen = flipColumn($screen,1,1);
*/

$n=0;
while ($line = trim(fgets(STDIN))) {
	print "> [$n]" . $line . "\n";
	if(strpos($line,'rect') === 0) {
		preg_match('/rect (\d+)x(\d+)/', $line, $matches);
		$screen = rect($screen,$matches[1],$matches[2]);
	}
	elseif(strpos($line,'rotate row') === 0) {
		preg_match('/rotate row y=(\d+) by (\d+)/', $line, $matches);
		$screen = flipRow($screen,$matches[1],$matches[2]);
	}
	elseif(strpos($line,'rotate column') === 0) {
		preg_match('/rotate column x=(\d+) by (\d+)/', $line, $matches);
		$screen = flipColumn($screen,$matches[1],$matches[2]);
	}
	printScreen($screen);
	$n++;
}

printScreen($screen);
echo "Lights on: " . lightsOnScreen($screen) . "\n";

function printScreen($screen) {
	for($y=0;$y<ScreenY;$y++) {
		for($x=0;$x<ScreenX;$x++) {
			echo $screen[$x][$y];
		}
		echo "\n";
	}
	echo "\n";
}

function lightsOnScreen($screen) {
	$lights = 0;
	for($y=0;$y<ScreenY;$y++) {
		for($x=0;$x<ScreenX;$x++) {
			if($screen[$x][$y] == '#') $lights++;
		}
	}
	return $lights;
}

function flipRow($screen,$pos,$count) {
	$y = $pos;
	$newScreen = $screen;
	for($x=0;$x<ScreenX;$x++) {
		$newX = $x - $count;
		while($newX < 0) $newX += ScreenX;
		$newScreen[$x][$y] = $screen[$newX][$y];
//		echo "($x,$y)=($newX,$y) " . $screen[$x][$y] . ' => ' . $screen[$newX][$y] . "\n";
	}
	return $newScreen;
}

function flipColumn($screen,$pos,$count) {
	$x = $pos;
	$newScreen = $screen;
	for($y=0;$y<ScreenY;$y++) {
		$newY = $y - $count;
		while($newY < 0) $newY += ScreenY;
		$newScreen[$x][$y] = $screen[$x][$newY];
//		echo "($x,$y)=($x,$newY) " . $screen[$x][$y] . ' => ' . $screen[$x][$newY] . "\n";
	}
	return $newScreen;
}

function rect($screen,$width,$height) {
	for($y=0;$y<$height;$y++) {
		for($x=0;$x<$width;$x++) {
			$screen[$x][$y] = '#';
		}
	}
	return $screen;	
}
