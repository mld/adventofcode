<?php

namespace App\Day13;

class ShuttleSearch
{
    /** @var array<string> */
    protected array $schedule;
    protected int $earliestDeparture;

    /**
     * RainRisk constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
    }

    /**
     * @param array<mixed> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->earliestDeparture = (int)trim($rows[0]);
        $this->schedule = explode(',', trim($rows[1]));
        printf("Earliest departure: %d\nShuttles: %s\n", $this->earliestDeparture, join(' ', $this->schedule));
    }


    public function part1(): int
    {
        $shuttles = [];
        foreach ($this->schedule as $shuttle) {
            if ($shuttle == 'x') {
                continue;
            }
            $shuttles[] = (int)$shuttle;
        }
        $timestamp = $this->earliestDeparture;
        do {
            foreach ($shuttles as $shuttle) {
                if ($timestamp % $shuttle == 0) {
                    return $shuttle * ($timestamp - $this->earliestDeparture);
                }
            }
            $timestamp++;
        } while (true);
    }

    public function part2naive(): int
    {
        $departures = [];
        $maxId = 0;
        $maxKey = 0;
        $minId = PHP_INT_MAX;
        foreach ($this->schedule as $key => $value) {
            if ($value == 'x') {
                continue;
            }
            if ($value > $maxId) {
                $maxId = $value;
                $maxKey = $key;
            }
            if ($value < $minId) {
                $minId = $value;
            }
            $departures[] = $key;
        }
        $timestamp = intdiv(100000000000000, (int)$maxId) * (int)$maxId;
        printf("Starting point: %d, max id=%d, min id=%d\n", $timestamp, $maxId, $minId);
        do {
//            printf("%d:\n",$timestamp);
            $relTs = $timestamp - $maxKey;
            foreach ($departures as $departure) {
//                printf(
//                    "  (%d + %d) %% %d = %d ",
//                    $relTs,
//                    $departure,
//                    (int)$this->schedule[$departure],
//                    ($relTs + $departure) % (int)$this->schedule[$departure]
//                );
                if (($relTs + $departure) % (int)$this->schedule[$departure] == 0) {
                    // and we have a matching departure!
//                    printf(" OK\n",$this->schedule[$departure]);
                    continue;
                }
                // nope, we that timestamp didn't match up, moving on
//                printf(" NOK\n",$this->schedule[$departure]);
                $timestamp += $maxId;
                continue 2;
            }
            // if we reached this point, the timestamp matched on all subsequent departures
            return $timestamp;
        } while (true);
    }

    public function part2(): int
    {
        $shuttles = [];
        foreach ($this->schedule as $key => $shuttle) {
//            printf("%s => %s :: %d => %d\n",$key,$shuttle,(int)$key,(int)$shuttle);
            if ($shuttle == 'x') {
                continue;
            }
            $shuttles[(int)$key] = (int)$shuttle;
        }
//        print_r($shuttles);
        $x = self::findMinX($shuttles);

        $prod = array_reduce($shuttles, function ($carry, $item) {
            if ($carry == 0) {
                return $item;
            }
            $carry *= $item;
            return $carry;
        });

//        printf("x: %d\n", $x);
        return $prod - $x;
    }

    /**
     * Returns modulo inverse of a with respect to m using extended Euclid Algorithm.
     * Refer post for details: https://www.geeksforgeeks.org/multiplicative-inverse-under-modulo-m/
     * @param int $a
     * @param int $m
     * @return int
     */
    public static function inv(int $a, int $m): int
    {
        $m0 = $m;
        $x0 = 0;
        $x1 = 1;

        if ($m == 1) {
            return 0;
        }

        // Apply extended Euclid Algorithm
        while ($a > 1) {
            // q is quotient
            $q = (int)($a / $m);

            $t = $m;

            // m is remainder now, process
            // same as euclid's algo
            $m = $a % $m;
            $a = $t;

            $t = $x0;

            $x0 = $x1 - $q * $x0;

            $x1 = $t;
        }

        // Make x1 positive
        if ($x1 < 0) {
            $x1 += $m0;
        }

        return $x1;
    }

    /**
     * Given that values in list are pairwise coprime (gcd for every pair is 1)
     * Returns the smallest number x such that:
     * x % num[0] = rem[0],
     * x % num[1] = rem[1],
     * ..................
     * x % num[k-2] = rem[k-1]
     * where rem[] is array_keys($list) and num[] is $list
     * refer to post for details: https://www.geeksforgeeks.org/chinese-remainder-theorem-set-2-implementation/
     * @param array<int> $list
     * @return int
     */
    public static function findMinX(array $list): int
    {
        // Compute product of all numbers
        $prod = 1;
        foreach ($list as $rem => $num) {
            $prod *= $num;
        }
        printf("prod: %d\n", $prod);

        // Initialize result
        $result = 0;

        // Apply above formula
        foreach ($list as $rem => $num) {
            $pp = $prod / $num;
            printf(" > num: %d, rem: %d, pp: %d, inv: %d\n", $num, $rem, $pp, self::inv($pp, $num));

            $result += $rem * self::inv($pp, $num) * $pp;
        }

        return ($result % $prod);
    }
}
