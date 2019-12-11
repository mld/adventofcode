<?php


namespace App\Day11;


use App\Day09\StateMachine;

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
        $this->stateMachine = new StateMachine($this->steps, false);
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
                $this->x--;
                break;
            case self::DIR_RIGHT:
                $this->x++;
                break;
            case self::DIR_UP:
                $this->y++;
                break;
            case self::DIR_DOWN:
                $this->y--;
                break;
        }
    }


    public function run()
    {
        $inputs = [];

        do {
//            printf("Color of %d,%d: %s\n", $this->x, $this->y, $this->getColor());
            $inputs[] = $this->getColor();
            $paintColor = $this->stateMachine->run($inputs);
//            printf("Painting %d,%d: %s\n", $this->x, $this->y, $paintColor);
            $this->paint($paintColor);
            $move = $this->stateMachine->run($inputs);
            printf("Pointing %s and moving %s from %d,%d\n", self::DIRECTIONS[$this->direction],
                self::DIRECTIONS[$move], $this->x, $this->y);
            $this->turn($move);
            $this->move();
        } while (!$this->stateMachine->isTerminated());

        if ($this->debug) {
            printf("Run ended with output %d\n", $this->coloredTiles);
        }

        return $this->coloredTiles;
    }
}