<?php
$doorid = 'reyedfim';
//$doorid = 'abc';

$n = 0;
$chars = 0;
$password = '________';

do {
	$test = $doorid . $n;
	$md5 = md5($test);
	if(strpos($md5,'00000') === 0) {
		switch($md5[5]) {
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
				if($password[$md5[5]] == '_') $password[$md5[5]] = $md5[6];
				$chars++;
				echo $md5 . ' : ' . $password . "\n";
				break;
				;;
			default:
				;;
		}
	}
	$n++;
} while(strpos($password,'_') !== false);

echo "$password\n";
