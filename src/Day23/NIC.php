<?php


namespace App\Day23;


use App\Day13\Computer;

class NIC
{
    protected $code;
    protected $debug;
    protected $computer;
    protected $input;
    protected $output;
    protected $idle;

    public function __construct($address = 0, $code = '99', $debug = false)
    {
        $this->code = $code;
        $this->debug = $debug;
        $this->input = [];
        $this->output = [];
        $this->computer = new Computer($this->code, true, $debug);
        $this->computer->input($address);
        $this->idle = 0;
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