<?php


namespace App\Day09;


class StateMachine
{
    protected $currentStep;
    protected $steps;
    protected $debug;
    protected $input;
    protected $terminated;
    protected $output;
    protected $base;

    public function __construct($steps = [], $debug = false)
    {
        $this->base = 0;
        $this->currentStep = 0;
        $this->debug = $debug;
        $this->steps = $steps;
        $this->input = [];
        $this->terminated = false;
        $this->output = null;
    }

    public function getStep($step)
    {
        if (!isset($this->steps[$step])) {
            return 0;
        }
        return $this->steps[$step];
    }

    public function getArgument($position, $argument = 1, $mode = 0)
    {
        $findStep = $position + $argument;

        $arg['position'] = $this->getStep($findStep);
        $arg['direct'] = $findStep;
        $arg['relative'] = $this->getStep($findStep) + $this->base;

        switch ($mode) {
            case 0:
                return $arg['position'];
                break;
            case 1:
                return $arg['direct'];
                break;
            case 2:
                return $arg['relative'];
                break;
        }

        return 0;
    }

    public function decode($step)
    {
        $instruction = '00000';

        if (isset($this->steps[$step])) {
            $instruction = sprintf("%05d", $this->steps[$step]);
        }

        $parts = str_split($instruction);
        $opcode = intval($parts[3] . $parts[4]);

        return [$opcode, intval($parts[2]), intval($parts[1]), intval($parts[0])];
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
            $instruction = $this->decode($this->currentStep);

            $opcode = $instruction[0];
            $modes = [$instruction[1], $instruction[2], $instruction[3]];

            $a = $this->getArgument($this->currentStep, 1, $instruction[1]);
            $b = $this->getArgument($this->currentStep, 2, $instruction[2]);
            $c = $this->getArgument($this->currentStep, 3, $instruction[3]);


            if ($this->debug) {
                printf(
                    "Step/base %d/%d: Instruction: %05d, Opcode: %02d, Modes: %d,%d,%d, Params: %d/%d,%d/%d,%d/%d\n",
                    $this->currentStep,
                    $this->base,
                    $this->getStep($this->currentStep),
                    $opcode,
                    $modes[0], $modes[1], $modes[2],
                    $a, $this->steps[$a],
                    $b, $this->steps[$b],
                    $c, $this->steps[$c]
                );
            }
            switch ($opcode) {
                case 1:
                    // addition
                    if ($this->debug) {
                        printf(
                            " - Add %d (%d) to %d (%d) and store it at %d\n",
                            $a, $this->getStep($a),
                            $b, $this->getStep($b),
                            $c
                        );
                    }
                    $this->steps[$c] = $this->getStep($a) + $this->getStep($b);
                    $this->currentStep += 4;
                    break;

                case 2:
                    // multiplication
                    if ($this->debug) {
                        printf(
                            " - Multiply %d (%d) with %d (%d) and store it at %d\n",
                            $a, $this->getStep($a),
                            $b, $this->getStep($b),
                            $c
                        );
                    }
                    $this->steps[$c] = $this->getStep($a) * $this->getStep($b);
                    $this->currentStep += 4;
                    break;
                case 3:
                    // Save input - ignore mode
                    $inp = array_shift($this->input);
                    if ($this->debug) {
                        printf(" - Take input %d and store it at %d (%d)\n", $inp, $a, $this->getStep($a));
                    }
                    $this->steps[$a] = $inp;
                    $this->currentStep += 2;
                    break;
                case 4:
                    // Save output - ignore mode?
                    $this->output = $this->getStep($a);
                    if ($this->debug) {
                        printf(" - Output %d (%d)\n", $this->output,
                            $a);
                    }
                    $this->currentStep += 2;
                    return $this->output;
                    break;
                case 5:
                    // Opcode 5 is jump-if-true: if the first parameter is non-zero, it sets the instruction pointer to
                    // the value from the second parameter. Otherwise, it does nothing.
                    if ($this->debug) {
                        printf(" - Set pointer to %d if %d is non-zero\n", $this->getStep($b), $this->getStep($a));
                    }
                    if ($this->getStep($a) != 0) {
                        $this->currentStep = $this->getStep($b);
                    } else {
                        $this->currentStep += 3;
                    }
                    break;
                case 6:
                    // Opcode 6 is jump-if-false: if the first parameter is zero, it sets the instruction pointer to the
                    // value from the second parameter. Otherwise, it does nothing.
                    if ($this->debug) {
                        printf(" - Set pointer to %d if %d is zero\n", $this->getStep($b), $this->getStep($a));
                    }
                    if ($this->getStep($a) == 0) {
                        $this->currentStep = $this->getStep($b);
                    } else {
                        $this->currentStep += 3;
                    }

                    break;
                case 7:
                    // Opcode 7 is less than: if the first parameter is less than the second parameter, it stores 1 in
                    // the position given by the third parameter. Otherwise, it stores 0.
                    if ($this->debug) {
                        printf(" - If %d (%d) is less than %d (%d), %d is set to 1, otherwise 0\n",
                            $a, $this->getStep($a),
                            $b, $this->getStep($b), $c);
                    }
                    if ($this->getStep($a) < $this->getStep($b)) {
                        $this->steps[$c] = 1;
                    } else {
                        $this->steps[$c] = 0;
                    }
                    $this->currentStep += 4;

                    break;
                case 8:
                    // Opcode 8 is equals: if the first parameter is equal to the second parameter, it stores 1 in the
                    // position given by the third parameter. Otherwise, it stores 0.
                    if ($this->debug) {
                        printf(" - If %d is equal to %d, %d is set to 1, otherwise 0\n", $a, $b, $c);
                    }
                    if ($this->getStep($a) == $this->getStep($b)) {
                        $this->steps[$c] = 1;
                    } else {
                        $this->steps[$c] = 0;
                    }
                    $this->currentStep += 4;
                    break;
                case 9:
                    // adjusts the relative base
                    if ($this->debug) {
                        printf(" - Adjust relative base with %d (%d)\n", $a, $this->getStep($a));
                    }
                    $this->base += $this->getStep($a);
                    $this->currentStep += 2;
                    break;

                default:
                    // End of program, but we never should get here...
                    if ($this->debug) {
                        printf("Step %d: This never happens...\n", $this->currentStep);
                    }
                    $this->currentStep++;
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