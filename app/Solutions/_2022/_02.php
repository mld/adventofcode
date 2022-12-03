<?php

namespace App\Solutions\_2022;

use App\Solution;

class _02 extends Solution
{

    # A > Z
    # B > X
    # C > Y

    protected $guide = [];

    protected $scoring = [
        'rock' => 1,
        'paper' => 2,
        'scissors' => 3,
        'lose' => 0,
        'draw' => 3,
        'win' => 6,
    ];

    public function __construct(array $attributes = [])
    {
        // $this->year and $this->day is set through attributes, and $this->raw is populated from puzzle input
        parent::__construct($attributes);

        foreach ($this->getLinesFromRaw() as $line) {
            $this->guide[] = explode(' ', trim($line));
        }
    }

    public function part1(): int
    {
        $hand = [
            'A' => 'rock',
            'B' => 'paper',
            'C' => 'scissors',
            'X' => 'rock',
            'Y' => 'paper',
            'Z' => 'scissors',
        ];

        $rules = [];
        $rules['rock']['rock'] = 'draw';
        $rules['rock']['paper'] = 'lose';
        $rules['rock']['scissors'] = 'win';
        $rules['paper']['rock'] = 'win';
        $rules['paper']['paper'] = 'draw';
        $rules['paper']['scissors'] = 'lose';
        $rules['scissors']['rock'] = 'lose';
        $rules['scissors']['paper'] = 'win';
        $rules['scissors']['scissors'] = 'draw';

        $score = 0;
        foreach ($this->guide as $step) {
            $opponent = $hand[$step[0]];
            $response = $hand[$step[1]];
            $score += $this->scoring[$opponent] + $this->scoring[$rules[$response][$opponent]];
        }
        return $score;
    }

    public function part2(): int
    {
        // TODO: Implement part2() method.


        $hand = [
            'A' => 'rock',
            'B' => 'paper',
            'C' => 'scissors',
            'X' => 'lose',
            'Y' => 'draw',
            'Z' => 'win',
        ];

        $rules = [];
        $rules['rock']['draw'] = 'rock';
        $rules['rock']['lose'] = 'scissors';
        $rules['rock']['win'] = 'paper';
        $rules['paper']['win'] = 'scissors';
        $rules['paper']['draw'] = 'paper';
        $rules['paper']['lose'] = 'rock';
        $rules['scissors']['lose'] = 'paper';
        $rules['scissors']['win'] = 'rock';
        $rules['scissors']['draw'] = 'scissors';

        $score = 0;

        foreach ($this->guide as $step) {
            $opponent = $hand[$step[0]];
            $outcome = $hand[$step[1]];
            $score += $this->scoring[$outcome] + $this->scoring[$rules[$opponent][$outcome]];
        }

        return $score;
    }
}
