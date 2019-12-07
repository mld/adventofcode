<?php


namespace App\Day07;


class StateMachine
{
    protected $currentStep;
    protected $steps;
    protected $debug;
    protected $input;
    protected $terminated;
    protected $output;

    public function __construct($steps = [], $debug = false)
    {
        $this->currentStep = 0;
        $this->debug = $debug;
        $this->steps = $steps;
        $this->input = [];
        $this->terminated = false;
        $this->output = null;
    }

    public function run($input = [])
    {
        // Handle input
        if (!is_array($input)) {
            $this->input[] = $input;

        } else {
            foreach ($input as $item) {
                $this->input[] = $item;
            }
        }

        while (!$this->isTerminated()) {
            $instruction = sprintf("%05d", $this->steps[$this->currentStep]);
            $parts = str_split($instruction);

            $opcode = intval($parts[3] . $parts[4]);
            $modes = [intval($parts[2]), intval($parts[1]), intval($parts[0])];

            $arg = [];
            for ($n = 0; $n < 3; $n++) {
                $arg[$n]['position'] = $this->steps[$this->steps[$this->currentStep + $n + 1]];
                $arg[$n]['direct'] = $this->steps[$this->currentStep + $n + 1];
                $arg[$n]['byMode'] = $modes[$n] == 0 ? $arg[$n]['position'] : $arg[$n]['direct'];
            }
            if ($this->debug) {
                printf("Instruction: %s, Step %d: Opcode: %02d, Modes: %d,%d,%d\n", $instruction, $this->currentStep,
                    $opcode,
                    $modes[0], $modes[1], $modes[2]);
            }
            switch ($opcode) {
                case 1:
                    // addition
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];

                    if ($this->debug) {
                        printf("Step %d: Add %d to %d and store it at place %d\n", $this->currentStep, $a, $b, $pos);
                    }
                    $this->steps[$pos] = $a + $b;
                    $this->currentStep += 4;
                    break;

                case 2:
                    // multiplication
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];
                    if ($this->debug) {
                        printf("Step %d: Multiply %d with %d and store it at place %d\n", $this->currentStep, $a, $b,
                            $pos);
                    }
                    $this->steps[$pos] = $a * $b;
                    $this->currentStep += 4;
                    break;
                case 3:
                    // Save input - ignore mode
                    $pos = $arg[0]['direct'];
                    $inp = array_shift($this->input);
                    if ($this->debug) {
                        printf("Step %d: Take input %d and store it at place %d\n", $this->currentStep, $inp, $pos);
                    }
                    $this->steps[$pos] = $inp;
                    $this->currentStep += 2;
                    break;
                case 4:
                    // Save output - ignore mode?
                    $pos = $arg[0]['direct'];
                    if ($this->debug) {
                        $nextInstruction = sprintf("%05d", $this->steps[$this->currentStep + 2]);
                        printf("Step %d: Save place %d as output %d, cur: %s, next: %s\n", $this->currentStep, $pos,
                            $this->steps[$pos], $instruction, $nextInstruction);
                    }
                    $this->output = $this->steps[$pos];
                    $this->currentStep += 2;

                    return $this->output;
                    break;
                case 5:
                    // Opcode 5 is jump-if-true: if the first parameter is non-zero, it sets the instruction pointer to
                    // the value from the second parameter. Otherwise, it does nothing.
                    $a = $arg[0]['byMode'];
                    $pos = $arg[1]['byMode'];
                    if ($this->debug) {
                        printf("Step %d: Set pointer to %d if %d (currentStep %d) is non-zero\n", $this->currentStep,
                            $pos, $a,
                            $arg[0]['direct']);
                    }
                    if ($a != 0) {
                        $this->currentStep = $pos;
                    } else {
                        $this->currentStep += 3;
                    }
                    break;
                case 6:
                    // Opcode 6 is jump-if-false: if the first parameter is zero, it sets the instruction pointer to the
                    // value from the second parameter. Otherwise, it does nothing.
                    $a = $arg[0]['byMode'];
                    $pos = $arg[1]['byMode'];

                    if ($this->debug) {
                        printf("Step %d: Set pointer to %d if %d is zero\n", $this->currentStep, $pos, $a);
                    }
                    if ($a == 0) {
                        $this->currentStep = $pos;
                    } else {
                        $this->currentStep += 3;
                    }

                    break;
                case 7:
                    // Opcode 7 is less than: if the first parameter is less than the second parameter, it stores 1 in
                    // the position given by the third parameter. Otherwise, it stores 0.
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];

                    if ($this->debug) {
                        printf("Step %d: If %d is less than %d, %d is set to 1, otherwise 0\n", $this->currentStep, $a,
                            $b, $pos);
                    }
                    if ($a < $b) {
                        $this->steps[$pos] = 1;
                    } else {
                        $this->steps[$pos] = 0;
                    }
                    $this->currentStep += 4;

                    break;
                case 8:
                    // Opcode 8 is equals: if the first parameter is equal to the second parameter, it stores 1 in the
                    // position given by the third parameter. Otherwise, it stores 0.
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];

                    if ($this->debug) {
                        printf("Step %d: If %d is equal to %d, %d is set to 1, otherwise 0\n", $this->currentStep, $a,
                            $b, $pos);
                    }
                    if ($a == $b) {
                        $this->steps[$pos] = 1;
                    } else {
                        $this->steps[$pos] = 0;
                    }
                    $this->currentStep += 4;
                    break;

                default:
                    // End of program, but we never should get here...
                    if ($this->debug) {
                        printf("Step %d: This never happens...\n", $this->currentStep);
                    }
                    break;
            }
        }

        if ($this->debug) {
            printf("Run ended with %d, output %d\n", $this->steps[0], $this->output);
        }

        return $this->output;
    }

    /**
     * @return bool
     */
    public function isTerminated(): bool
    {
        $instruction = sprintf("%05d", $this->steps[$this->currentStep]);
        $parts = str_split($instruction);

        $opcode = intval($parts[3] . $parts[4]);
        return $opcode == 99;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }
}