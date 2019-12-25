<?php


namespace App\Day24;


class Eris
{
    protected $debug;
    protected $map;
    protected $raw;

    public function __construct($raw, $code = '99', $debug = false)
    {
        $this->debug = $debug;
        $this->raw = $raw;

        foreach (range(-1, 5) as $x) {
            foreach (range(-1, 5) as $y) {
                $this->map[$x][$y] = ' ';
            }
        }

        $this->parseRaw();

    }

    public function parseRaw()
    {
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                if ($this->raw[$y][$x] == '#') {
                    $this->map[$x][$y] = '#';
                }
                if ($this->raw[$y][$x] == '.') {
                    $this->map[$x][$y] = '.';
                }
            }
        }
    }

    public function printMap()
    {
        foreach (range(-1, 5) as $y) {
            foreach (range(-1, 5) as $x) {
                printf("%s", $this->map[$x][$y]);
            }
            printf("\n");
        }
        $rating = 0;
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                if ($this->map[$x][$y] == '#') {
                    $rating += pow(2, ($x + $y * 5));
                }
            }
        }
        printf("Biodiversity rating: %d\n",$rating);
    }

    public function tick(): int
    {
        // Build map of adjacent bugs
        $neighbours = [];
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                $neighbours[$x][$y] = 0;
                $neighbours[$x][$y] += $this->map[$x - 1][$y] == '#' ? 1 : 0;
                $neighbours[$x][$y] += $this->map[$x + 1][$y] == '#' ? 1 : 0;
                $neighbours[$x][$y] += $this->map[$x][$y + 1] == '#' ? 1 : 0;
                $neighbours[$x][$y] += $this->map[$x][$y - 1] == '#' ? 1 : 0;
            }
        }

        // Update map
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                if ($this->map[$x][$y] == '#' && $neighbours[$x][$y] != 1) {
                    $this->map[$x][$y] = '.';
                } elseif ($this->map[$x][$y] == '.' && ($neighbours[$x][$y] == 1 || $neighbours[$x][$y] == 2)) {
                    $this->map[$x][$y] = '#';
                }
            }
        }

        // Return biodiversity rating
        $rating = 0;
        foreach (range(0, 4) as $x) {
            foreach (range(0, 4) as $y) {
                if ($this->map[$x][$y] == '#') {
                    $rating += pow(2, ($x + $y * 5));
                }
            }
        }

        return $rating;
    }

    public function findDuplicate(): int
    {
        $this->printMap();
        $ratings = [];
        do {
            if (isset($rating)) {
                $ratings[$rating] = true;
            }
            $rating = $this->tick();
            $this->printMap();
            printf("\n");
        } while (!isset($ratings[$rating]));
        return $rating;
    }

    public function run(): void
    {
        // To send a packet to another computer, the NIC will use three output instructions that provide the destination
        // address of the packet followed by its X and Y values. For example, three output instructions that provide the
        // values 10, 20, 30 would send a packet with X=20 and Y=30 to the computer with address 10.
        //
        // To receive a packet from another computer, the NIC will use an input instruction. If the incoming packet
        // queue is empty, provide -1. Otherwise, provide the X value of the next packet; the computer will then use a
        // second input instruction to receive the Y value for the same packet. Once both values of the packet are read
        // in this way, the packet is removed from the queue.
        //
        // Note that these input and output instructions never block. Specifically, output instructions do not wait for
        // the sent packet to be received - the computer might send multiple packets before receiving any. Similarly,
        // input instructions do not wait for a packet to arrive - if no packet is waiting, input instructions should
        // receive -1.

        if ($this->computer->pauseReason == 'input') {
            $item = array_shift($this->input);
            if ($item == null) {
                $this->computer->input(-1);
                $this->idle++;
            } elseif (!is_array($item)) {
                $this->computer->input($item); // x
                $this->idle = 0;
            } else {
                $this->computer->input($item[0]); // x
                $this->computer->input($item[1]); // y
                $this->idle = 0;
            }
            return;
        }

        if ($this->computer->pauseReason == 'output') {
            $dst = $this->computer->output;
            $this->computer->run(); //
            $x = $this->computer->output;
            $this->computer->run();
            $y = $this->computer->output;
            $this->computer->run();

            array_push($this->output, [$dst, $x, $y]);
            return;
        }

        return;
    }

    public function input($x, $y = null)
    {
        if ($y === null) {
            return array_push($this->input, $x);
        }
        return array_push($this->input, [$x, $y]);
    }

    public function output()
    {
        return array_shift($this->output);
    }

    public function idle()
    {
        return ($this->idle > 1);
    }
}