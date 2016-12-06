<?php
$doorid = 'reyedfim';
//$doorid = 'abc';

$n = 0;
$password = '';

do {
	$test = $doorid . $n;
	$md5 = md5($test);
	if(strpos($md5,'00000') === 0) {
		$password .= $md5[5];
		echo $md5 . ' : ' . $password . "\n";
	}
	$n++;
} while(strlen($password) < 8);

echo "$password\n";
