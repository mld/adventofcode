<?php


namespace App\Day02;


class StateMachine
{
    protected $steps;
    protected $debug;

    public function __construct($steps = [])
    {
        $this->debug = false;
        $this->steps = $steps;
    }

    public function run()
    {
        if (count($this->steps) == 0) {
            return -1;
        }

        $step = 0;
        if($this->debug) printf("\n");
        if($this->debug) var_export($this->steps);
        if($this->debug) printf("\n");

        do {
            switch ($this->steps[$step]) {
                case 1:
                    // addition
                    $a = $this->steps[$this->steps[$step + 1]];
                    $b = $this->steps[$this->steps[$step + 2]];
                    $pos = $this->steps[$step + 3];
                    if($this->debug) printf("Step %d: Add %d to %d and store it at place %d\n", $step, $a, $b, $pos);
                    $this->steps[$pos] = $a + $b;
                    break;
                case 2:
                    // multiplication
                    $a = $this->steps[$this->steps[$step + 1]];
                    $b = $this->steps[$this->steps[$step + 2]];
                    $pos = $this->steps[$step + 3];
                    if($this->debug) printf("Step %d: Multiply %d with %d and store it at place %d\n", $step, $a, $b, $pos);
                    $this->steps[$pos] = $a * $b;
                    break;
                default:
                    // End of program, but we never should get here...
                    if($this->debug) printf("This never happens...\n");
                    break;
            }
            $step += 4;
        } while ($this->steps[$step] != 99);

        if($this->debug) printf("\n");
        if($this->debug) var_export($this->steps);
        if($this->debug) printf("\n");
        if($this->debug) printf("Run ended with %d\n", $this->steps[0]);

        return $this->steps[0];
    }

}