<?php

namespace App\Day22;

class CrabCombat
{
    /**
     * @var array<mixed>
     */
    protected array $decks;

    /**
     * CrabCombat constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        $players = explode("\n\n", trim(join('', $input)));

        foreach ($players as $player) {
            $rows = explode("\n", trim($player));
            $item = array_shift($rows);
            sscanf($item, 'Player %d:', $playerNumber);
            $this->decks[(int)$playerNumber] = array_map('intval', $rows);
        }
    }

    public function part1(): int
    {
        $round = 0;
        do {
            $round++;
            $hand = [
                1 => array_shift($this->decks[1]),
                2 => array_shift($this->decks[2]),
            ];
            arsort($hand, SORT_NUMERIC);
            reset($hand);
            $winner = key($hand);
            $this->decks[$winner] = array_merge($this->decks[$winner], $hand);
        } while (count($this->decks[1]) != 0 && count($this->decks[2]) != 0);

        $winner = count($this->decks[1]) > 0 ? 1 : 2;

        return $this->calculateScore($this->decks, $winner);
    }

    public function part2(): int
    {
        $score = 0;
        $this->recursiveCombat($this->decks, 1, $score);
        return $score;
    }

    /**
     * @param array<int,array> $decks
     * @return string
     */
    protected function decksToString(array $decks): string
    {
        return sprintf('%s|%s', join(',', $decks[1]), join(',', $decks[2]));
    }

    /**
     * @param array<int,array> $decks
     * @param int $winner
     * @return int
     */
    protected function calculateScore(array $decks, int $winner): int
    {
        if (!isset($decks[$winner]) || count($decks[$winner]) == 0) {
            return 0;
        }
        $score = 0;
        $multiplier = count($decks[$winner]);
        foreach ($decks[$winner] as $card) {
            $score += $multiplier * $card;
            $multiplier--;
        }
        return $score;
    }

    /**
     * @param array<int,array> $decks
     * @param int $game
     * @param int $score
     * @return int
     */
    protected function recursiveCombat(array $decks, int $game = 1, int &$score = 0): int
    {
        $previousRounds = [];
        $round = 0;
        $winner = 0;
        do {
            $round++;

            $deckString = $this->decksToString($decks);
            if (in_array($deckString, $previousRounds)) {
                $winner = 1;
                $score = $this->calculateScore($decks, $winner);
                return $winner;
            }
            $previousRounds[] = $deckString;

            $hand = [
                1 => array_shift($decks[1]),
                2 => array_shift($decks[2]),
            ];

            if ($hand[1] <= count($decks[1]) && $hand[2] <= count($decks[2])) {
                $subDeck[1] = array_slice($decks[1], 0, $hand[1]);
                $subDeck[2] = array_slice($decks[2], 0, $hand[2]);
                $winner = $this->recursiveCombat($subDeck, $game + 1, $score);
            } else {
                arsort($hand, SORT_NUMERIC);
                reset($hand);
                $winner = key($hand);
            }

            // the winning hand now depends on which card won, not the highest card
            $winningHand = [
                $winner == 1 ? $hand[1] : $hand[2],
                $winner == 1 ? $hand[2] : $hand[1],
            ];
            $decks[$winner] = array_merge($decks[$winner], $winningHand);
        } while (count($decks[1]) != 0 && count($decks[2]) != 0);

        $score = $this->calculateScore($decks, $winner);
        return $winner;
    }
}
