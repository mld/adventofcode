<?php


namespace App\Day19;


use App\Day13\Computer;
use App\Day15\Point;

class TractorBeam
{
    protected $code;
    protected $map;
    protected $debug;
    protected $height;
    protected $width;


    public function __construct($code = '99', $height = 10, $width = 10, $debug = false)
    {
        $this->code = $code;
        $this->debug = $debug;
        $this->height = $height;
        $this->width = $width;
    }

    protected function check($x, $y)
    {
        $code = $this->code;
        $computer = new Computer($code, true, false);
        $computer->input($x);
        $computer->input($y);
        if ($this->debug) {
            printf("%s: output: %s\n", new Point($x, $y), $computer->output);
        }
        return $computer->output;
    }

    public function scan()
    {
        $x = 0;
        $y = 0;
        $beamSize = 0;
        do {
            $out = $this->check($x, $y);

            if ($out == 1) {
                $beamSize++;
            }
            $this->map[$x][$y] = $out;

            $x++;
            if ($x == $this->width) {
                $x = 0;
                $y++;
            }

        } while ($y < $this->width);

        return $beamSize;
    }

    public function range($height, $width)
    {
        // Find the range where the beam fits around an object with arguments as height and width
        $x = 0;
        $y = 0;
        while ($this->check($x + $width - 1, $y) == 0) {
            $y++;
            if ($this->check($x, $y + $height - 1) == 0) {
                $x++;
            }
        }

        return new Point($x, $y);
    }

    public function printField($map = null)
    {
        if ($map == null) {
            $map = $this->map;
        }
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
                printf("  ");
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
                        case 1:
                            printf($xFormat, '#');
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