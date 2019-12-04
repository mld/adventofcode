<?php


namespace App\Day04;


class Passwords
{
    public static function DoubleAscendingCheck($password)
    {
        $double = 0;
        $ascending = 0;
        for ($i = 0; $i < 6; $i++) {
            $a = substr($password, $i, 1);
            $b = null;
            if ($i > 0) {
                $b = substr($password, $i - 1, 1);

                if ($a == $b) {
                    $double = true;
                }
                if ($a >= $b) {
                    $ascending = true;
                }
                if ($a < $b) {
                    $ascending = false;
//                    printf("Password %s false (dbl: %d, asc %d)\n", substr($password, 0, $i + 1), $double, $ascending);
                    return false;
                }
            };
//            printf("Password %d (dbl: %d, asc %d) (%s == %s)\n", substr($password, 0, $i + 1), $double, $ascending, $a,$b);
        }

        return $double && $ascending;
    }

    public static function DoubleAscendingNoTripleCheck($password)
    {
        $double = 0;
        $repeated = 0;
        $ascending = 0;

        for ($i = 0; $i < 6; $i++) {
            $a = substr($password, $i, 1);
            $b = null;
            $c = null;
            if ($i > 0) {
                $b = substr($password, $i - 1, 1);

                if ($a == $b) {
                    $ascending++;

                    $repeated++;
                    if ($repeated == 1) {
                        $double++;
                    } elseif ($repeated == 2) {
                        $double--;
                    }
                }
                if ($a > $b) {
                    $repeated = 0;
                    $ascending++;
                }

                if ($a < $b) {
                    $ascending = 0;
                    $repeated = 0;
//                    printf("Password %d false (rpt: %d, asc %d, dbl: %d) (%s == %s == %s)\n",
//                        substr($password, 0, $i + 1),
//                        $repeated, $ascending, $double, $a, $b, $c);

                    return false;
                }

//                if ($i > 1) {
//                    $c = substr($password, $i - 2, 1);
//                    if ($a == $b && $b == $c) {
//                        $double = false;
////                        $repeated -=;
//                    }
//                }
            };
//            printf("Password %d (rpt: %d, asc: %d, dbl: %d) (%s == %s == %s)\n", substr($password, 0, $i + 1),
//                $repeated,
//                $ascending, $double, $a, $b, $c);
        }

        return ($double > 0) && ($ascending > 0);
    }
}