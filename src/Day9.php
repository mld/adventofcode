<?php
declare(strict_types=1);

namespace App;


use Illuminate\Support\Collection;

class Day9
{
    public static function sum($input)
    {
        $a = new Collection(self::parseInput($input));

        $children = new Collection([]);
        $meta = new Collection([]);

        while ($a->count() > 0) {
            $readNewNode = true;
            if ($children->count() > 0) {
                // keep reading children...
                $parent = $children->pop();
                if ($parent['children'] == 0) {
                    $noChildren = 0;
                    $noMeta = $parent['metadata'];
//                    echo "Read parent node " . $noChildren . ':' . $noMeta . "\n";
                    $readNewNode = false;
                } else {
//                    echo "Keeping parent node for later " . ($parent['children'] - 1) . ':' . $parent['metadata'] . "\n";
                    $children->push(['children' => ($parent['children'] - 1), 'metadata' => $parent['metadata']]);
                }
            }

            if ($readNewNode) {
                // Read new "parent"
                $noChildren = $a->shift();
                $noMeta = $a->shift();
//                echo "Read node " . $noChildren . ':' . $noMeta . "\n";
            }

            if ($noChildren == 0) {
                // Read metadata directly
//                echo "Saving metadata for node: ";
                for ($n = 0; $n < $noMeta; $n++) {
                    $data = $a->shift();
//                    echo "$data ";
                    $meta->push($data);
                }
//                echo "\n";
            } else {
//                echo "Saving parent node for later " . $noChildren . ':' . $noMeta . "\n";
                $children->push(['children' => $noChildren, 'metadata' => $noMeta]);
            }

        }

        $sum = $meta->sum();


        return $sum;
    }

    public static function parseInput($input)
    {
        if (is_array($input)) {
            $input = trim(join('', $input));
        }

        $out = explode(' ', $input);
        return $out;
    }
}
