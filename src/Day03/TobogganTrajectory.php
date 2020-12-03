<?php

namespace App\Day03;

class TobogganTrajectory
{
    /** @var array<mixed> */
    protected $map;

    /**
     * TobogganTrajectory constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $y = 0;
        foreach ($rows as $row) {
            if (strlen(trim($row)) > 0) {
                $this->map[$y] = str_split(trim($row));
                $y++;
            }
        }
    }

    public function printMap(): void
    {
        foreach (array_keys($this->map) as $y) {
            printf("% 3d: ", $y);
            foreach ($this->map[$y] as $column) {
                printf("%s ", $column);
            }
            printf("\n");
        }
        printf("\n");
    }

    public function getCoordinate(int $posX, int $posY): string
    {
        if (!isset($this->map[$posY])) {
//            printf("(%d,%d) translated into (%d,%d): unknown row\n", $posX, $posY, $posX, $posY);
            return ' ';
        }

        $x = $posX;
        $mapWidth = count($this->map[$posY]);
        if ($posX >= $mapWidth) {
            $x = $posX % $mapWidth;
        }
        if (isset($this->map[$posY][$x])) {
//            printf("(%d,%d) translated into (%d,%d): %s\n", $posX, $posY, $x, $posY, $this->map[$posY][$x]);
            return $this->map[$posY][$x];
        }
//        printf("(%d,%d) translated into (%d,%d): unknown\n", $posX, $posY, $x, $posY);
        return ' ';
    }

    public function traverseMap(int $moveX = 3, int $moveY = 1): int
    {
        $posX = 0;
        $posY = 0;
        $trees = 0;
        do {
            $posX += $moveX;
            $posY += $moveY;
            $position = $this->getCoordinate($posX, $posY);
            if ($position == '#') {
                $trees++;
            }
        } while ($position !== ' ');
        return $trees;
    }
}
