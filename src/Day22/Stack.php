<?php


namespace App\Day22;


use function Sodium\increment;

class Stack
{
    protected $instructions;
//    protected $cards;
    protected $numberOfCards;
    protected $debug;

    public function __construct($instructions = [], $cards = 10007, $debug = false)
    {
        $this->instructions = $instructions;
        $this->debug = $debug;
//        $this->cards = range(0, $cards - 1);
        $this->numberOfCards = $cards;
    }

//    public function reset()
//    {
//        sort($this->cards, SORT_NUMERIC);
//    }
//
//    public function find($searchForCard)
//    {
//        $n = 0;
//        foreach ($this->cards as $card) {
//            if ($card == $searchForCard) {
//                return $n;
//            }
//            $n++;
//        }
//        return false;
//    }
//
//    public function dealNew()
//    {
//        $this->cards = array_reverse($this->cards);
//    }
//
//    public function cut($numberOfCards)
//    {
//        if ($numberOfCards > 0) {
//            for ($n = 0; $n < $numberOfCards; $n++) {
//                $card = array_shift($this->cards);
//                array_push($this->cards, $card);
//            }
//        } else {
//            for ($n = 0; $n < abs($numberOfCards); $n++) {
//                $card = array_pop($this->cards);
//                array_unshift($this->cards, $card);
//            }
//        }
//    }
//
//    public function increment($increment)
//    {
//        $numberOfCards = count($this->cards);
//        $position = 0;
//        $newDeck = array_merge($this->cards);
////        echo 'C: ' . join(' ', $this->cards) . "\n";
//
//        $count = 0;
//        do {
//            $card = array_shift($this->cards);
//            $newDeck[$position] = $card;
//
////            printf("Put card %s in position %d\n", $card, $position);
//            $position += $increment;
//            if ($position >= $numberOfCards) {
//                $position -= $numberOfCards;
//            }
//            $count++;
//        } while ($count < $numberOfCards);
//
////        echo 'C: ' . join(' ', $this->cards) . "\n";
////        echo 'N: ' . join(' ', $newDeck) . "\n";
//        $this->cards = $newDeck;
//    }
//
//    public function __toString()
//    {
//        return count($this->cards) . ': ' . join(' ', $this->cards);
//    }

//    public function shuffleByInstructions()
//    {
////        printf("starting deck: %s\n", $this);
//
//        foreach ($this->instructions as $n => $instruction) {
//            if (strcmp('deal into new stack', trim($instruction)) === 0) {
//                printf("%d: deal into new stack\n", $n);
//                $this->dealNew();
//            } elseif (strpos(trim($instruction), 'deal') === 0) {
//                sscanf($instruction, "deal with increment %s", $m);
//                printf("%d: deal with increment  %d\n", $n, $m);
//
//                $this->increment($m);
//            } elseif (strpos(trim($instruction), 'cut') === 0) {
//                sscanf($instruction, "cut %d", $m);
//                printf("%d: cut %d\n", $n, $m);
//                $this->cut($m);
//            }
////            printf("%d: %s, deck: %s\n", $n, $instruction, $this);
//        }
//
////        printf("Ending deck: %s\n", $this);
//    }

    public function findAfterInstructions($searchForCard)
    {
        $position = $searchForCard;

        foreach ($this->instructions as $n => $instruction) {
            if (strcmp('deal into new stack', trim($instruction)) === 0) {
                $position = ($this->numberOfCards - $position - 1) % $this->numberOfCards;
//                printf("%d/%d: deal into new stack: %d\n", $searchForCard, $n, $position);

            } elseif (strpos(trim($instruction), 'deal') === 0) {
                sscanf($instruction, "deal with increment %s", $m);
                $position = $position * $m % $this->numberOfCards;
//                printf("%d/%d: deal with increment %d: %d\n", $searchForCard, $n, $m, $position);

            } elseif (strpos(trim($instruction), 'cut') === 0) {
                sscanf($instruction, "cut %d", $m);
                $position = ($position - $m + $this->numberOfCards) % $this->numberOfCards;

//                printf("%d/%d: cut %d: %d\n", $searchForCard, $n, $m, $position);

            }
            printf("%d/%d: instruction %d\n", $searchForCard, $n, $position);
        }

        printf("%d/e: pos %d\n", $searchForCard, $position);

        return $position;
    }

    public static function advancedShuffle(
        $instructions = [],
        $position = 2020,
        $numberOfCards = 119315717514047,
        $iterations = 101741582076661
    ) {
        //  Part 2 requires knowledge of math that I simply do not have (anymore?). I implemented a variation of the logic found here:
        //
        //  https://www.reddit.com/r/adventofcode/comments/ee0rqi/2019_day_22_solutions/fbnkaju/

        $incrementMultiplier = 1;
        $offsetDiff = 0;
        // replace all operators with gmp_<operator>...
        foreach ($instructions as $n => $instruction) {
            printf("%d/1: instruction %s, %s/%s\n", $n, $instruction, $offsetDiff, $incrementMultiplier);

            if (strcmp('deal into new stack', trim($instruction)) === 0) {
                $incrementMultiplier *= -1;
                $offsetDiff += $incrementMultiplier;

            } elseif (strpos(trim($instruction), 'deal') === 0) {
                sscanf($instruction, "deal with increment %s", $m);
                printf(" > increment: %s, exponent: %s, modulo: %s, pow: %d\n",$m,$numberOfCards-2,$numberOfCards,pow($m, $numberOfCards - 2) );
                $incrementMultiplier *= (pow($m, $numberOfCards - 2) % $numberOfCards);

            } elseif (strpos(trim($instruction), 'cut') === 0) {
                sscanf($instruction, "cut %d", $m);
                $offsetDiff += $m * $incrementMultiplier;

            }
            printf("%d/2: instruction %s, %s/%s\n", $n, $instruction, $offsetDiff, $incrementMultiplier);
            $incrementMultiplier %= $numberOfCards;
            $offsetDiff %= $numberOfCards;
            printf("%d/3: instruction %s, %s/%s\n", $n, $instruction, $offsetDiff, $incrementMultiplier);
        }

        $increment = pow($incrementMultiplier, $iterations) % $numberOfCards;
        printf("Increment: %s\n",$increment);

        $offset = ($offsetDiff *
            (1 - $increment) *
            pow((1 - $incrementMultiplier) % $numberOfCards, $numberOfCards - 2)) % $numberOfCards;
        printf("Offset: %s\n",$offset);


        $offset %= $numberOfCards;
        printf("Offset: %s\n",$offset);

        $card = ($offset + ($position - 1) * $increment) % $numberOfCards;
        printf("Card at position %d after %d iterations: %d, offset: %s, increment: %s\n", $position, $iterations, $card,$offset,$increment);

        return $card;
    }

}