<?php
declare(strict_types=1);

namespace App;

class Day2
{
    public static function checksum($data)
    {
        $twins = 0;
        $triplets = 0;

        foreach ($data as $row) {
            $twins += self::nOfM(2, $row);
            $triplets += self::nOfM(3, $row);
        }

        return $twins * $triplets;
    }

    public static function nOfM($n, $m)
    {
        $chars = str_split(trim($m), 1);
        $duplicates = [];
        foreach ($chars as $char) {
            $duplicates[$char] = isset($duplicates[$char]) ? $duplicates[$char] + 1 : 1;
        }

        foreach ($duplicates as $char => $count) {
            if ($count == $n) {
                return 1;
            }
        }
        return 0;
    }

    public static function fabricId($data)
    {
        foreach ($data as $word1) {
            foreach ($data as $word2) {
                if (levenshtein($word1, $word2) == 1) {
                    $fabricId = '';
                    for ($n = 0; $n < strlen($word1);  $n++) {
                        if($word1[$n] == $word2[$n]) {
                            $fabricId .= $word1[$n];
                        }
                    }
                    return $fabricId;
                }
            }
        }
    }
}
