<?php


namespace App\Day11;


class EmergencyHullPaintingRobot
{
    protected $map;
    protected $steps;
    protected $debug;
    protected $stateMachine;
    protected $direction;
    protected $coloredTiles;
    public const DIR_LEFT = 0;
    public const DIR_RIGHT = 1;
    public const DIR_UP = 2;
    public const DIR_DOWN = 3;
    public const DIRECTIONS = ['left', 'right', 'up', 'down'];

    public function __construct($steps = [], $debug = false)
    {
        $this->coloredTiles = 0;
        $this->map = [];
        $this->steps = $steps;
        $this->x = 0;
        $this->y = 0;
        $this->debug = $debug;
        $this->direction = self::DIR_UP;
    }

    public function paint($color)
    {
        if (!isset($this->map[$this->x][$this->y])) {
            $this->coloredTiles++;
        }
        $this->map[$this->x][$this->y] = $color;
    }

    public function getColor()
    {
        if (isset($this->map[$this->x][$this->y])) {
            return $this->map[$this->x][$this->y];
        }
        return 0;
    }

    public function turn($direction)
    {
        switch ($direction) {
            case self::DIR_LEFT:
                switch ($this->direction) {
                    case self::DIR_LEFT:
                        $this->direction = self::DIR_DOWN;
                        break;
                    case self::DIR_RIGHT:
                        $this->direction = self::DIR_UP;
                        break;
                    case self::DIR_UP:
                        $this->direction = self::DIR_LEFT;
                        break;
                    case self::DIR_DOWN:
                        $this->direction = self::DIR_RIGHT;
                        break;
                }
                break;

            case self::DIR_RIGHT:
                switch ($this->direction) {
                    case self::DIR_LEFT:
                        $this->direction = self::DIR_UP;
                        break;
                    case self::DIR_RIGHT:
                        $this->direction = self::DIR_DOWN;
                        break;
                    case self::DIR_UP:
                        $this->direction = self::DIR_RIGHT;
                        break;
                    case self::DIR_DOWN:
                        $this->direction = self::DIR_LEFT;
                        break;
                }
                break;
        }
    }

    public function move()
    {
        switch ($this->direction) {
            case self::DIR_LEFT:
                $this->x++;
                break;
            case self::DIR_RIGHT:
                $this->x--;
                break;
            case self::DIR_UP:
                $this->y--;
                break;
            case self::DIR_DOWN:
                $this->y++;
                break;
        }
    }


    public function run()
    {
        //$filename = __DIR__ . '/inputs/09.txt';
        //
        //$code = file_get_contents($filename);
        //// test sequence
        ////$code = '109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99';
        //$pc = new Computer($code, true, true, 1);
        //// change to 2 for second part.
        //$pc->input(1);
        //echo "\n" . $pc->output . "\n";
        $inputs = [];

        $this->stateMachine = new Computer(join(",", $this->steps), false, true);

        do {
//            printf("Color of %d,%d: %s\n", $this->x, $this->y, $this->getColor());
//            $inputs[] = $this->getColor();
            $this->stateMachine->input($this->getColor());
//            printf("Painting %d,%d: %s\n", $this->x, $this->y, $paintColor);
            $paintColor = $this->stateMachine->output;
            $this->paint($paintColor);
            $this->stateMachine->run();
            $move = $this->stateMachine->output;
            printf("Pointing %s and moving %s from %d,%d\n", self::DIRECTIONS[$this->direction],
                self::DIRECTIONS[$move], $this->x, $this->y);
            $this->turn($move);
            $this->move();
        } while ($this->stateMachine->running);

        if ($this->debug) {
            printf("Run ended with output %d\n", $this->coloredTiles);
        }

        $minY = 0;
        $maxY = 0;
        $minX = min(array_keys($this->map));
        $maxX = max(array_keys($this->map));

        foreach (array_keys($this->map) as $x) {
            if ($minY > min(array_keys($this->map[$x]))) {
                $minY = min(array_keys($this->map[$x]));
            }
            if ($maxY < max(array_keys($this->map[$x]))) {
                $maxY = max(array_keys($this->map[$x]));
            }
        }

        $n = 0;
        for($y = $minY;$y<=$maxY;$y++) {
            for($x = $minX;$x<=$maxX;$x++) {
                if(isset($this->map[$x][$y])) {
                    if($this->map[$x][$y] == 1) echo '#';
                    if($this->map[$x][$y] == 0) echo '.';
                    $n++;
                }
                else echo '_';
            }
            echo "\n";
        }
        echo "\n";
        echo "n: $n\n";
        return $this->coloredTiles;
    }
}