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
            $this->computers[$n] = new NIC($n, $this->code, false);
        }
    }

    public function idle(): bool
    {
        $status = 0;
        foreach (array_keys($this->computers) as $computer) {
            if ($this->computers[$computer]->idle()) {
                $status++;
            }
        }

        return ($status == count($this->computers));
    }

    public function step(): void
    {
        foreach (array_keys($this->computers) as $computer) {
//            printf("Running NIC %d\n", $computer);
            $this->computers[$computer]->run();
            $output = $this->computers[$computer]->output();
            if ($output !== null) {
                printf("NIC %d sent x: %s, y: %s to address %d\n", $computer, $output[1], $output[2], $output[0]);
                if (isset($this->computers[intval($output[0])])) {
                    $this->computers[intval($output[0])]->input($output[1], $output[2]);
                }
                $this->output[intval($output[0])][] = [$output[1], $output[2]];
            }
//            printf(" - Idle: %b\n", $this->computers[$computer]->idle());
        }
        printf("System idle status: %b\n", $this->idle());
    }

    public function findYTo255()
    {
        $nat = null;
        $lastNat = null;
        $out = null;
        do {
            $this->step();
            if (isset($this->output[255])) {
                $out = array_shift($this->output[255]);
            }
        } while ($out === null);

        return intval($out[1]);
    }

    public function nat()
    {
        $nat = null;
        $return = [];
        $out = null;
        $this->output[255] = [];
        do {
            $this->step();
            // Pick the last message, discard anything left
            $out = array_pop($this->output[255]);
            if ($out !== null) {
                $this->output[255] = [];
                $nat = $out;
            }

            if ($this->idle() && count($nat) > 1) {
//                if ($nat !== null) {
//                    $return = $nat;
//                }
                printf("NIC %d sent x: %s, y: %s to address %d\n", 255, $nat[1], $nat[2], 0);
                $this->computers[0]->input($nat[0], $nat[1]);
                $return[] = $nat[1];
                $return = array_slice($return, -2);
            }

            if (count($return) == 2 && $return[1] == $return[0]) {
                printf("Done: return: %s, nat: %s\n", print_r($return, true), print_r($nat, true));
            }
        } while (count($return) < 2 || $return[1] !== $return[0]);

        print_r($return);
        return $return[1];
    }

}