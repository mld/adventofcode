<?php

namespace App\Day12;

class RainRisk
{
    /** @var array<string> */
    protected array $commands;

    /**
     * RainRisk constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
        print_r($this->commands);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->commands = [];
        foreach ($rows as $y => $row) {
            if (strlen(trim($row)) > 0) {
                $this->commands[$y] = trim($row);
            }
        }
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $command
     * @param array<int> $heading
     */
    public function printPosition(int $x, int $y, string $command = '', array $heading = [1, 0]): void
    {
        $directions[0][1] = 'N';
        $directions[0][-1] = 'S';
        $directions[1][0] = 'E';
        $directions[-1][0] = 'W';
        $direction = $directions[$heading[0]][$heading[1]];
        $xPosition = ($x < 0 ? 'west' : 'east');
        $yPosition = ($y < 0 ? 'south' : 'north');
        $xCommand = strlen($command) > 0 ? sprintf('% -4s ', $command) : '';
        printf(
            "%s%s % 3d, %s % 3d, heading %s\n",
            $xCommand,
            $xPosition,
            abs($x),
            $yPosition,
            abs($y),
            $direction
        );
    }

    /**
     * @param array<int> $direction
     * @param string $action
     * @param int $distance
     * @return array<int>
     */
    public function direction(array $direction, string $action, int $distance): array
    {
        switch ($action) {
            case 'N':
                $direction = [0, 1];
                break;
            case 'S':
                $direction = [0, -1];
                break;
            case 'E':
                $direction = [1, 0];
                break;
            case 'W':
                $direction = [-1, 0];
                break;
            case 'L':
                $left[0][1] = [90 => 'W', 180 => 'S', 270 => 'E']; // north
                $left[1][0] = [90 => 'N', 180 => 'W', 270 => 'S']; // east
                $left[0][-1] = [90 => 'E', 180 => 'N', 270 => 'W']; // south
                $left[-1][0] = [90 => 'S', 180 => 'E', 270 => 'N']; // west

                $direction = $this->direction($direction, $left[$direction[0]][$direction[1]][$distance], 0);
                break;
            case 'R':
                $right[0][1] = [90 => 'E', 180 => 'S', 270 => 'W']; // north
                $right[1][0] = [90 => 'S', 180 => 'W', 270 => 'N']; // east
                $right[0][-1] = [90 => 'W', 180 => 'N', 270 => 'E']; // south
                $right[-1][0] = [90 => 'N', 180 => 'E', 270 => 'S']; // west

                $direction = $this->direction($direction, $right[$direction[0]][$direction[1]][$distance], 0);
                break;
        }
        return $direction;
    }


    public function part1(): int
    {
        $pos = [0, 0];
        $direction = [1, 0];
        $this->printPosition($pos[0], $pos[1], '***');
        foreach ($this->commands as $command) {
            $action = $command[0];
            $distance = (int)substr($command, 1);

            $move = $direction;
            switch ($action) {
                case 'N':
                case 'S':
                case 'E':
                case 'W':
                    $move = $this->direction($direction, $action, $distance);
                    break;
                case 'L':
                case 'R':
                    $move = $this->direction($direction, $action, $distance);
                    $direction = $move;
                    $distance = 0;
                    break;
            }
            $pos[0] += $move[0] * $distance;
            $pos[1] += $move[1] * $distance;
//            $this->printPosition($pos[0], $pos[1], $command, $direction);
        }
        return (abs($pos[0]) + abs($pos[1]));
    }

    public function part2(): int
    {
        $pos = [0, 0];
        $waypoint = [10, 1];
//        $this->printPosition($pos[0], $pos[1], '***');
        foreach ($this->commands as $command) {
            $action = $command[0];
            $distance = (int)substr($command, 1);

            switch ($action) {
                case 'N':
                    $waypoint[1] += $distance;
                    break;
                case 'S':
                    $waypoint[1] -= $distance;
                    break;
                case 'E':
                    $waypoint[0] += $distance;
                    break;
                case 'W':
                    $waypoint[0] -= $distance;
                    break;
                case 'L':
                    switch ($distance) {
                        case 90:
                            $old = $waypoint;
                            $waypoint[0] = -$old[1];
                            $waypoint[1] = $old[0];
                            break;
                        case 180:
                            $waypoint[0] *= -1;
                            $waypoint[1] *= -1;
                            break;
                        case 270:
                            $old = $waypoint;
                            $waypoint[0] = $old[1];
                            $waypoint[1] = -$old[0];
                            break;
                    }
                    break;
                case 'R':
                    switch ($distance) {
                        case 90:
                            $old = $waypoint;
                            $waypoint[0] = $old[1];
                            $waypoint[1] = -$old[0];
                            break;
                        case 180:
                            $waypoint[0] *= -1;
                            $waypoint[1] *= -1;
                            break;
                        case 270:
                            $old = $waypoint;
                            $waypoint[0] = -$old[1];
                            $waypoint[1] = $old[0];
                            break;
                    }
                    break;
                case 'F':
                    $pos[0] += $waypoint[0] * $distance;
                    $pos[1] += $waypoint[1] * $distance;
                    break;
            }
//            $this->printPosition($waypoint[0], $waypoint[1], $command, $direction);
        }
        return (abs($pos[0]) + abs($pos[1]));
    }
}
