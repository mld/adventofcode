<?php

namespace App\Day08;

class HandheldHalting
{
    /** @var array<mixed> */
    protected array $stack;

    protected int $step = 0;

    protected int $accumulator = 0;

    /**
     * Passports constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->stack = [];
        foreach ($rows as $row) {
            $parts = explode(" ", trim($row));
            if (is_array($parts) && count($parts) == 2) {
                $this->stack[] = [$parts[0], (int)$parts[1]];
            }
        }
        printf("Found %d commands\n", count($this->stack));
    }

    public function reset(): void
    {
        $this->step = 0;
        $this->accumulator = 0;
    }

    public function switchOperators(): int
    {
        $originalStack = $this->stack;

        foreach ($originalStack as $line => $command) {
            $this->reset();
            $this->stack = $originalStack;
            switch ($this->stack[$line][0]) {
                case 'jmp':
                    $this->stack[$line][0] = 'nop';
                    break;
                case 'nop':
                    $this->stack[$line][0] = 'jmp';
                    break;
                default:
                    continue 2;
            }
//            $this->printStack($line);
            $returnStatus = $this->runUntilComplete();
//            printf("> Completed with status: %b\n", $returnStatus);
            if ($returnStatus) {
                return $this->accumulator;
            }
            printf("%d: Detected loop on step %d\n", $line, $this->step);
        }
        return PHP_INT_MIN;
    }

    public function runUntilComplete(): bool
    {
        $steps = [];
        do {
            $steps[] = $this->step();
//            printf("Next step: % 3d, previous steps: %s\n", $this->step, join(',', $steps));
        } while (!in_array($this->step, $steps) && isset($this->stack[$this->step]));

        return !isset($this->stack[$this->step]);
    }


    public function runUntilLoop(): int
    {
        $this->printStack();
        $steps = [];
        do {
            $steps[] = $this->step();
//            printf("Next step: %03d, previous steps: %s\n", $this->step, join(',', $steps));
        } while (!in_array($this->step, $steps) || !isset($this->stack[$this->step]));
        return $this->accumulator;
    }

    protected function step(): int
    {
        $currentStep = $this->step;

        if (!isset($this->stack[$currentStep])) {
            return $currentStep;
        }

        $op = $this->stack[$currentStep][0];
        $arg = $this->stack[$currentStep][1];
//        printf("Executing step %03d: %s %d\n", $this->step, $op, $arg);
        switch ($op) {
            case 'nop':
                $this->step++;
                break;
            case 'acc':
                $this->accumulator += $arg;
                $this->step++;
                break;
            case 'jmp':
                $this->step += $arg;
                break;
        }
        return $currentStep;
    }

    public function printStack(int $mark = -1): void
    {
        foreach ($this->stack as $key => $value) {
            $current = ($key == $this->step ? ' => ' : '    ');
            if ($key == $mark) {
                $current[0] = '*';
            }
            printf("%s%03d: %s %d\n", $current, $key, $value[0], $value[1]);
        }
    }
}
