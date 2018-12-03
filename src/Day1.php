<?php
declare(strict_types=1);

namespace App;

class Day1
{
    public static function getFrequency($data = [])
    {
        $sum = 0;
        foreach ($data as $entry) {
            $sum += intval(trim($entry));
        }
        return $sum;
    }

    public static function getDuplicate($data = [])
    {
        $sum = 0;
        $set = [0];
        while (true) {
            foreach ($data as $entry) {
                $sum += intval(trim($entry));

                if (isset($set[$sum])) {
                    return $sum;
                }

                $set[$sum] = true;
            }
        }
    }
}
