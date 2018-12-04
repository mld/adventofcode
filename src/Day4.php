<?php
declare(strict_types=1);

namespace App;


class Day3
{
    public static function calculateOverlap($data)
    {
        $sheet = self::map($data);

        $overlap = 0;
        foreach (array_keys($sheet) as $x) {
            foreach (array_keys($sheet[$x]) as $y) {
                if (count($sheet[$x][$y]) > 1) {
                    $overlap++;
                }
            }
        }

        return $overlap;
    }

    public static function map($data)
    {
        $sheet = [];

        foreach ($data as $cmd) {
            $map = self::parse($cmd);

            for ($x = $map['left']; $x < ($map['left'] + $map['wide']); $x++) {
                for ($y = $map['top']; $y < ($map['top'] + $map['tall']); $y++) {
                    $sheet[$x][$y][] = $map['id'];
                }
            }
        }

        return $sheet;
    }

    public static function parse($data)
    {
        // '#1 @ 1,3: 4x4',
        // ['id' => 1, 'left' => 1, 'top' => 3, 'wide' => 4, 'tall' => 4]

        list($id, $left, $top, $wide, $tall) = sscanf($data, '#%d @ %d,%d: %dx%d');

        return ['id' => $id, 'left' => $left, 'top' => $top, 'wide' => $wide, 'tall' => $tall];
    }

    public static function noOverlap($data)
    {
        $sheet = self::map($data);
        $idMap = [];

        foreach (array_keys($sheet) as $x) {
            foreach (array_keys($sheet[$x]) as $y) {
                foreach ($sheet[$x][$y] as $id) {
                    $idMap['all'][$id] = 1;
                    if (count($sheet[$x][$y]) > 1) {
                        $idMap['remove'][$id] = 1;
                    }
                }
            }
        }

        foreach (array_keys($idMap['remove']) as $id) {
            unset($idMap['all'][$id]);
        }
        $unique = array_flip($idMap['all']);

        return array_shift($unique);
    }
}
