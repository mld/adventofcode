<?php


namespace App\Day17;


use App\Day13\Computer;

class TractorBeam
{
    protected $code;
    protected $debug;
    protected $height;


    public function __construct($code = '99', $height = 10, $width = 10, $debug = false)
    {
        $this->code = $code;
        $this->debug = $debug;
    }

    public function beam()
    {
        $code = $this->code;
        $code[0] = 2; // wake up the robot

        $commands = [
            0 => str_split('A,B,B,C,B,C,B,C,A,A' . chr(10)), // Main routine
            1 => str_split('L,6,R,8,L,4,R,8,L,12' . chr(10)), // Function A
            2 => str_split('L,12,R,10,L,4' . chr(10)), // Function B
            3 => str_split('L,12,L,6,L,4,L,4' . chr(10)), // Function C
            4 => str_split('n' . chr(10)), // Video feed
        ];
        $command = 0;
        $pos = 0;

        $computer = new Computer($code, true, false);
        $last = null;
        do {
            if ($computer->pauseReason == 'output') {
                if (chr($computer->output) == '.') {
                    printf(" ");
                } else {
                    printf("%c", $computer->output);
                }
                $computer->run();

            }
            if ($computer->pauseReason == 'input') {
                $computer->input(ord($commands[$command][$pos]));
                if (ord($commands[$command][$pos]) == 10) {
                    $command++;
                    $pos = 0;
                } else {
                    $pos++;
                }
            }
        } while ($computer->running || $command <= 3);

        return $computer->output;
    }

    public function printMap($map)
    {
        $minX = PHP_INT_MAX;
        $maxX = PHP_INT_MIN;
        $minY = min(array_keys($map));
        $maxY = max(array_keys($map));

        foreach (array_keys($map) as $y) {
            if ($minX > min(array_keys($map[$y]))) {
                $minX = min(array_keys($map[$y]));
            }
            if ($maxX < max(array_keys($map[$y]))) {
                $maxX = max(array_keys($map[$y]));
            }
        }

        printf("Printing map: x: %d - %d, y: %d - %d\n", $minX, $maxX, $minY, $maxY);

        $xFormat = "%3s";
        $n = 0;
        $header = true;
        for ($y = $minY - 2; $y <= $maxY + 1; $y++) {
            if (!$header) {
                printf("%3d: ", $y);
            } else {
                printf("     ");
            }
            for ($x = $minX - 1; $x <= $maxX + 1; $x++) {
                if ($header) {
                    printf("%3d", trim($x));
                    continue;
                }
                if (isset($map[$y][$x])) {
                    switch ($map[$y][$x]) {
                        case '.':
                            printf($xFormat, ' ');
                            break;

                        case '#':
                        default:
                            printf($xFormat, trim($map[$y][$x]));

                    }
                    $n++;
                }
            }
            $header = false;
            echo "\n";
        }
    }
}