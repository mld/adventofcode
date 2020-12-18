<?php

namespace App\Day18;

use function Symfony\Component\String\s;

class OperationOrder
{
    /**
     * @var array<string>
     */
    protected array $input;

    /** @var array<int> */
    protected array $occurrences;

    /**
     * RainRisk constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->input = [];
        foreach ($rows as $row) {
            $this->input[] = str_replace(' ', '', trim($row));
        }
    }

    /**
     * @param string $expression
     * @param int $depth
     * @return int
     */
    public function parse(string $expression, int $depth = 0): int
    {
        do {
            $pos = strrpos($expression, '(');
            if ($pos !== false) {
                $rpos = strpos($expression, ')', $pos);
                // we've got parenthesis - go, go, go!

                $expression = sprintf(
                    '%s%s%s',
                    substr($expression, 0, $pos),
                    $this->parse(substr($expression, $pos + 1, $rpos - $pos - 1), $depth + 1),
                    substr($expression, $rpos + 1),
                );
            }
        } while (strpos($expression, '(') !== false);

        $parts = preg_split('/(\+|\*)/', $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if ($parts === false) {
            return 0;
        }

        $sum = 0;
        $operator = '+';
        foreach ($parts as $key => $part) {
            switch ($part) {
                case '+':
                case '*':
                    $operator = $part;
                    break;
                default:
                    if ($operator == '+') {
                        $sum += (int)$part;
                    } elseif ($operator == '*') {
                        $sum *= (int)$part;
                    }
            }
        }

        return $sum;
    }


    /**
     * @param string $expression
     * @param int $depth
     * @return int
     */
    public function parseAdvanced(string $expression, int $depth = 0): int
    {
        do {
            $pos = strrpos($expression, '(');
            if ($pos !== false) {
                $rpos = strpos($expression, ')', $pos);
                // we've got parenthesis - go, go, go!
                $expression = sprintf(
                    '%s%s%s',
                    substr($expression, 0, $pos),
                    $this->parseAdvanced(
                        substr($expression, $pos + 1, $rpos - $pos - 1),
                        $depth + 1
                    ),
                    substr($expression, $rpos + 1),
                );
            }
        } while (strpos($expression, '(') !== false);

        $parts = preg_split('/(\+|\*)/', $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if ($parts === false) {
            return 0;
        }

        foreach (['+', '*'] as $operator) {
            while (in_array($operator, $parts)) {
                foreach ($parts as $key => $part) {
                    if ($part == $operator) {
                        if ($operator == '+') {
                            $val = (int)$parts[$key - 1] + (int)$parts[$key + 1];
                        } else {
                            $val = (int)$parts[$key - 1] * (int)$parts[$key + 1];
                        }
                        array_splice($parts, $key - 1, 3, [$val]);
                        continue 2;
                    }
                }
            }
        }

        return (int)$parts[0];
    }

    public function part1(): int
    {
        $results = [];
        foreach ($this->input as $expression) {
            $results[$expression] = $this->parse($expression);
        }
        return (int)array_sum($results);
    }

    public function part2(): int
    {
        $results = [];
        foreach ($this->input as $expression) {
            $results[$expression] = $this->parseAdvanced($expression);
        }
        return (int)array_sum($results);
    }
}
