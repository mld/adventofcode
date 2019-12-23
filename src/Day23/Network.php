<?php


namespace App\Day23;


class Network
{
    protected $code;
    protected $debug;
    protected $computers;
    protected $output;

    public function __construct($code = '99', $computers = 50, $debug = false)
    {
        $this->code = $code;
        $this->debug = $debug;

        foreach (range(0, $computers - 1) as $n) {
            $this->computers[$n] = new NIC($this->code, $n == 0);
        }
    }

    public function step(): void
    {
        foreach (array_keys($this->computers) as $computer) {
            $this->computers[$computer]->run();
            $output = $this->computers[$computer]->output();
            if ($output !== null) {
                if (isset($this->computers[intval($output[0])])) {
                    $this->computers[intval($output[0])]->input($output[1], $output[2]);
                } else {
                    printf("Computer %d sent x: %s, y: %s to computer %d\n", $computer, $output[1], $output[2],
                        $output[0]);
                }
                $this->output[intval($output[0])][] = [$output[1], $output[2]];
            }
        }
    }

    public function findYTo255()
    {
        do {
            $this->step();

            $out = array_shift($this->output[255]);
        } while ($out === null);

        return $out;
    }


}