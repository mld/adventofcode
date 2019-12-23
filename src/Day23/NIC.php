<?php


namespace App\Day15;


use App\Day13\Computer;

class NIC
{
    protected $code;
    protected $debug;
    protected $computer;
    protected $input;
    protected $output;

    public function __construct($code = '99', $debug = false)
    {
        $this->code = $code;
        $this->debug = $debug;
        $this->input = [];
        $this->output = [];
        $this->computer = new Computer($this->code, false, false);
    }

    public function execute()
    {
        $result = false;

        //To send a packet to another computer, the NIC will use three output instructions that provide the destination address of the packet followed by its X and Y values. For example, three output instructions that provide the values 10, 20, 30 would send a packet with X=20 and Y=30 to the computer with address 10.
        //
        //To receive a packet from another computer, the NIC will use an input instruction. If the incoming packet queue is empty, provide -1. Otherwise, provide the X value of the next packet; the computer will then use a second input instruction to receive the Y value for the same packet. Once both values of the packet are read in this way, the packet is removed from the queue.
        //
        //Note that these input and output instructions never block. Specifically, output instructions do not wait for the sent packet to be received - the computer might send multiple packets before receiving any. Similarly, input instructions do not wait for a packet to arrive - if no packet is waiting, input instructions should receive -1.

        $this->computer->run();
        $this->computer->input($x);
        $this->computer->input($y);
        $output = $this->computer->output;

        switch (intval($output)) {
            case 0: // wall
                //...;
                break;
        }


    }

}