<?php


namespace App\Day03;


class Wires
{
    protected $wireMap1;
    protected $wireMap2;

    public const MANHATTAN_DISTANCE = 0;
    public const STEPS = 1;

    public function __construct($wireInstructionsString1, $wireInstructionsString2)
    {
        $wireInstructions1 = $this->parseString(trim($wireInstructionsString1));
        $this->wireMap1 = $this->createMap($wireInstructions1);

        $wireInstructions2 = $this->parseString(trim($wireInstructionsString2));
        $this->wireMap2 = $this->createMap($wireInstructions2);
    }

    public function parseString($str)
    {
        return explode(',', trim($str));
    }

    public function createMap($instructions)
    {
        $x = 0;
        $y = 0;
        $map[$x][$y] = 0;
        $steps = 0;

        foreach ($instructions as $instruction) {
            $direction = $instruction[0];
            $length = substr($instruction, 1);
            $directionX = 0;
            $directionY = 0;

            switch ($direction) {
                case 'U':
                    $directionY = 1;
                    break;
                case 'D':
                    $directionY = -1;
                    break;
                case 'R':
                    $directionX = 1;
                    break;
                case 'L':
                    $directionX = -1;
                    break;
            }

            for ($n = 0; $n < $length; $n++) {
                $x += $directionX;
                $y += $directionY;
                $steps++;

                if (!isset($map[$x][$y])) {
                    $map[$x][$y] = $steps;
                }
            }
        }

        return $map;
    }

    public function findClosestCrossing($type = self::MANHATTAN_DISTANCE)
    {
        $crossings = [];

        $intersectX = array_intersect_key($this->wireMap1, $this->wireMap2);
        foreach ($intersectX as $x => $itemX) {
            $intersectY = array_intersect_key($this->wireMap1[$x], $this->wireMap2[$x]);
            foreach ($intersectY as $y => $itemY) {
                if ($x == 0 && $y == 0) {
                    continue;
                }
                switch ($type) {
                    case self::MANHATTAN_DISTANCE:
                        $crossings[] = Distance::manhattan([0, 0], [$x, $y]);
                        break;
                    case self::STEPS:
                        $crossings[] = $this->wireMap1[$x][$y] + $this->wireMap2[$x][$y];
                        break;
                }
//                printf("(%d,%d) Steps: %d, Distance: %d\n",
//                    $x,
//                    $y,
//                    $this->wireMapSteps1[$x][$y] + $this->wireMapSteps2[$x][$y],
//                    Distance::manhattan([0, 0], [$x, $y])
//                );
            }
        }

        sort($crossings);
        return reset($crossings);
    }

}