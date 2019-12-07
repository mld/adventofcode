<?php


namespace App\Day07;


use App\Day07\StateMachine;

class Amplifier
{
    protected $steps;
    protected $debug;
    /** @var StateMachine[] $stateMachines */
    protected $stateMachines;

    public function __construct($steps = [], $debug = false)
    {
        $this->steps = $steps;
        $this->debug = $debug;

        for ($n = 0; $n < 5; $n++) {
            $inputSteps = array_merge($this->steps);
            $this->stateMachines[$n] = new StateMachine($inputSteps,  false);
        }
    }

    public function run($phases)
    {
        if (!is_array($phases)) {
            return false;
        }

        $amps = ['A', 'B', 'C', 'D', 'E'];
        $inputs = [];
        $output = [];
        foreach ($amps as $key => $amp) {
            $output[$key] = 0;
            $inputs[$key] = [$phases[$key]];
        }

        // Start with AMP A
        $active = 0;
        $lastActive = 0;
        do {
            switch ($active) {
                case 0:
                    $lastActive = 4;
//                    $nextActive = $active + 1;
                    break;
                case 1:
                case 2:
                case 3:
                    $lastActive = $active - 1;
//                    $nextActive = $active + 1;
                    break;
                case 4:
                    $lastActive = $active - 1;
//                    $nextActive = 0;
                    break;
                case 5:
                    $lastActive = 4;
                    $active = 0;
//                    $nextActive = $active + 1;
                    break;
            }

            // Prepare inputs
            $inputs[$active][] = $output[$lastActive];

            if ($this->debug) {
                printf(
                    "%s: AMP:%s started with input [%s]\n",
                    join('', $phases),
                    $amps[$active],
                    join(',', $inputs[$active])
                );
            }

            $output[$active] = $this->stateMachines[$active]->run($inputs[$active]);
            $inputs[$active] = [];

            if ($this->debug) {
                printf("%s: AMP:%s ended with output %d, terminated: %s\n", join('', $phases), $amps[$active],
                    $output[$active], var_export($this->stateMachines[4]->isTerminated(), true));

            }

            $active++;
        } while (!$this->stateMachines[4]->isTerminated());

        if ($this->debug) {
            printf("Run ended with output %d\n", $output[4]);
        }

        return $output[4];
    }
}