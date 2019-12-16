<?php


namespace App\Day16;


class FlawedFrequencyTransmission
{
    protected $numbers;
    protected $debug;


    public function __construct($numbers = [], $debug = false)
    {
        $this->numbers = $numbers;
        $this->debug = $debug;
    }

    public function part1()
    {
        $numbers = array_merge($this->numbers);
        $length = count($numbers);

        foreach (range(0, 99) as $step) {
            $old = array_merge($numbers);

            foreach(range(0, intdiv($length, 2)) as $i) {
                $j = $i;
                $m = $i + 1;
                $cur = 0;

                while ($j < $length) {
                    $cur += array_sum(array_slice($old, $j, $m, true));
                    $j += 2 * $m;

                    $cur -= array_sum(array_slice($old, $j, $m, true));
                    $j += 2 * $m;
                }

                $numbers[$i] = abs($cur) % 10;
            }

            foreach (range($length - 2, intdiv($length, 2)+1, -1) as $i) {
                $numbers[$i] += $numbers[$i + 1];
                $numbers[$i] %= 10;
            }
        }

        return join('', array_slice($numbers, 0, 8));
    }

    public function part2()
    {
        $skip = intval(join('', array_slice($this->numbers, 0, 7)));
        $numbers = str_split(substr(str_repeat(join('', $this->numbers), 10000), (int)$skip));
        $length = count($numbers);

        foreach (range(0, 99) as $step) {
            foreach (range($length - 2, -1, -1) as $i) {
                $numbers[$i] += $numbers[$i + 1];
                $numbers[$i] %= 10;
            }
        }
        return join('', array_slice($numbers, 0, 8));
    }
}