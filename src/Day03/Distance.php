<?php


namespace App\Day03;


class Distance
{
    public static function manhattan($v1, $v2)
    {
        $sum = 0;
        for ($i = 0; $i < 2; $i++) {
            $sum += abs($v1[$i] - $v2[$i]);
        }
        return $sum;
    }
}