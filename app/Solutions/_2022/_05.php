<?php

namespace App\Solutions\_2022;

use App\Solution;
use function Psy\debug;

class _05 extends Solution
{

    protected function getParsedInput(): array
    {
        $stacksRaw = [];
        $moves = [];
        $stacks = [];
        $parsingMoves = false;
        foreach ($this->getLinesFromRaw(false) as $line) {
            if ($parsingMoves) {
                $read = sscanf($line, 'move %d from %d to %d');
                if (!is_null($read)) {
                    $moves[] = $read;
                }
            }
            if (!$parsingMoves) {
                if (strlen(trim($line)) == 0) {
                    $parsingMoves = true;
                    continue;
                }
                $stacksRaw[] = $line;
            }
        }

        $numberOfStacks = array_pop($stacksRaw);
        $stacksRaw = array_reverse($stacksRaw);

        $stackOffset = [];
        for ($column = 1; $column < strlen($numberOfStacks); $column++) {
            if ((int)$numberOfStacks[$column] == 0) {
                continue;
            }
            $stackOffset[(int)$numberOfStacks[$column]] = $column;
        }

        foreach (array_keys($stackOffset) as $stack) {
            foreach ($stacksRaw as $item) {
                if (isset($item[$stackOffset[$stack]]) && strlen(trim($item[$stackOffset[$stack]])) > 0) {
                    $stacks[$stack][] = $item[$stackOffset[$stack]];
                }
            }
        }

        return ['stacks' => $stacks, 'moves' => $moves];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected function move(array &$stacks, int $number, int $from, int $to, bool $moveStacks = false): void
    {
//        for ($n = 0; $n < $number; $n++) {
//            $stacks[$to][] = array_pop($stacks[$from]);
//        }
        $slice = [];
        for ($n = 0; $n < $number; $n++) {
            $slice[] = array_pop($stacks[$from]);
        }
        if ($moveStacks) {
            $slice = array_reverse($slice);
        }
        $this->debug(sprintf("Moving %s from stack %d to stack %d\n", join(',', $slice), $from, $to));

        array_push($stacks[$to], ...$slice);
    }

    public function part1(): string
    {
        $cargo = $this->getParsedInput();

        foreach ($cargo['moves'] as $key => $move) {
            $this->move($cargo['stacks'], $move[0], $move[1], $move[2]);
        }

        $return = '';
        foreach (array_keys($cargo['stacks']) as $stack) {
            $return .= last($cargo['stacks'][$stack]);
        }
        return $return;
    }

    public function part2(): string
    {
        $cargo = $this->getParsedInput();

        foreach ($cargo['moves'] as $key => $move) {
            $this->move($cargo['stacks'], $move[0], $move[1], $move[2], true);
        }

        $return = '';
        foreach (array_keys($cargo['stacks']) as $stack) {
            $return .= last($cargo['stacks'][$stack]);
        }
        return $return;
    }
}
