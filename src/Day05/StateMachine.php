<?php


namespace App\Day05;


class StateMachine
{
    protected $steps;
    protected $debug;

    public function __construct($steps = [], $debug = false)
    {
        $this->debug = $debug;
        $this->steps = $steps;
    }

    public function run($input)
    {
        if (count($this->steps) == 0) {
            return -1;
        }

        $step = 0;
        $output = null;
        do {
            $instruction = sprintf("%05d", $this->steps[$step]);
            $parts = str_split($instruction);

            $opcode = intval($parts[3] . $parts[4]);
            $modes = [intval($parts[2]), intval($parts[1]), intval($parts[0])];

            $arg = [];
            for ($n = 0; $n < 3; $n++) {
                $arg[$n]['position'] = $this->steps[$this->steps[$step + $n + 1]];
                $arg[$n]['direct'] = $this->steps[$step + $n + 1];
                $arg[$n]['byMode'] = $modes[$n] == 0 ? $arg[$n]['position'] : $arg[$n]['direct'];
            }
            if ($this->debug) {
                printf("Instruction: %s, Step %d: Opcode: %02d, Modes: %d,%d,%d\n", $instruction, $step, $opcode,
                    $modes[0], $modes[1], $modes[2]);
            }
            switch ($opcode) {
                case 1:
                    // addition
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];

                    if ($this->debug) {
                        printf("Step %d: Add %d to %d and store it at place %d\n", $step, $a, $b, $pos);
                    }
                    $this->steps[$pos] = $a + $b;
                    $step += 4;
                    break;

                case 2:
                    // multiplication
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];
                    if ($this->debug) {
                        printf("Step %d: Multiply %d with %d and store it at place %d\n", $step, $a, $b, $pos);
                    }
                    $this->steps[$pos] = $a * $b;
                    $step += 4;
                    break;
                case 3:
                    // Save input - ignore mode
                    $pos = $arg[0]['direct'];
                    if ($this->debug) {
                        printf("Step %d: Take input %d and store it at place %d\n", $step, $input, $pos);
                    }
                    $this->steps[$pos] = $input;
                    $step += 2;
                    break;
                case 4:
                    // Save output - ignore mode?
                    $pos = $arg[0]['direct'];
                    if ($this->debug) {
                        printf("Step %d: Save place %d as output %d\n", $step, $pos, $this->steps[$pos]);
                    }
                    $output = $this->steps[$pos];
                    $step += 2;
                    break;
                case 5:
                    // Opcode 5 is jump-if-true: if the first parameter is non-zero, it sets the instruction pointer to
                    // the value from the second parameter. Otherwise, it does nothing.
                    $a = $arg[0]['byMode'];
                    $pos = $arg[1]['byMode'];
                    if ($this->debug) {
                        printf("Step %d: Set pointer to %d if %d (step %d) is non-zero\n", $step, $pos, $a, $arg[0]['direct']);
                    }
                    if ($a != 0) {
                        $step = $pos;
                    } else {
                        $step += 3;
                    }
                    break;
                case 6:
                    // Opcode 6 is jump-if-false: if the first parameter is zero, it sets the instruction pointer to the
                    // value from the second parameter. Otherwise, it does nothing.
                    $a = $arg[0]['byMode'];
                    $pos = $arg[1]['byMode'];

                    if ($this->debug) {
                        printf("Step %d: Set pointer to %d if %d is zero\n", $step, $pos, $a);
                    }
                    if ($a == 0) {
                        $step = $pos;
                    } else {
                        $step += 3;
                    }

                    break;
                case 7:
                    // Opcode 7 is less than: if the first parameter is less than the second parameter, it stores 1 in
                    // the position given by the third parameter. Otherwise, it stores 0.
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];

                    if ($this->debug) {
                        printf("Step %d: If %d is less than %d, %d is set to 1, otherwise 0\n", $step, $a, $b, $pos);
                    }
                    if ($a < $b) {
                        $this->steps[$pos] = 1;
                    } else {
                        $this->steps[$pos] = 0;
                    }
                    $step += 4;

                    break;
                case 8:
                    // Opcode 8 is equals: if the first parameter is equal to the second parameter, it stores 1 in the
                    // position given by the third parameter. Otherwise, it stores 0.
                    $a = $arg[0]['byMode'];
                    $b = $arg[1]['byMode'];
                    $pos = $arg[2]['direct'];

                    if ($this->debug) {
                        printf("Step %d: If %d is equal to %d, %d is set to 1, otherwise 0\n", $step, $a, $b, $pos);
                    }
                    if ($a == $b) {
                        $this->steps[$pos] = 1;
                    } else {
                        $this->steps[$pos] = 0;
                    }
                    $step += 4;
                    break;
                default:
                    // End of program, but we never should get here...
                    if ($this->debug) {
                        printf("This never happens...\n");
                    }
                    break;
            }
        } while ($this->steps[$step] != 99);

        if ($this->debug) {
            printf("Run ended with %d, output %d\n", $this->steps[0], $output);
        }

        return $output;
    }

}