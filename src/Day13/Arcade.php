<?php


namespace App\Day13;


class Arcade
{
    protected $steps;

    public function __construct($steps = [], $debug = false)
    {
        $this->steps = $steps;
    }

    protected function getMap()
    {
        $c = new Computer($this->steps, true, false);
        $map = [];

        while ($c->running) {
//            printf("Color of %d,%d: %s\n", $this->x, $this->y, $this->getColor());
//            $inputs[] = $this->getColor();
//            $this->stateMachine->input($this->getColor());
//            printf("Painting %d,%d: %s\n", $this->x, $this->y, $paintColor);
            $x = $c->output;
            $c->run();
            $y = $c->output;
            $c->run();
            $type = $c->output;
            $c->run();

            printf("%d,%d: %d\n", $x, $y, $type);
            $map[$y][$x] = $type;
        }
        return $map;
    }

    public function getBlockTiles($map = null, $verbose = false)
    {
        if ($map == null) {
            $c = new Computer($this->steps, true, false);

            $map = [];

            while ($c->running) {
//            printf("Color of %d,%d: %s\n", $this->x, $this->y, $this->getColor());
//            $inputs[] = $this->getColor();
//            $this->stateMachine->input($this->getColor());
//            printf("Painting %d,%d: %s\n", $this->x, $this->y, $paintColor);
                $x = $c->output;
                $c->run();
                $y = $c->output;
                $c->run();
                $type = $c->output;
                $c->run();

                printf("%d,%d: %d\n", $x, $y, $type);
                $map[$y][$x] = $type;
            }
        }

        $blocktiles = 0;
        foreach (array_keys($map) as $y) {
            foreach (array_keys($map[$y]) as $x) {
                if ($map[$y][$x] == 2) {
                    $blocktiles++;
                }
            }
        }

        if ($verbose) {
            $this->printMap($map);
        }

        return $blocktiles;
    }

    public function play()
    {

        $joystick = 0;
        $ballX = 0;
        $ballDirection = 0;
        $padX = 0;

        $map = [];
        $score = 0;
        $steps = $this->steps;
        $steps[0] = 2; // "coins inserted"
        $c = new Computer($steps, false, false);
        $c->setAutoInput(function () use ($map) {
            $paddle = null;
            $ball = null;
            foreach (array_keys($map) as $y) {
                foreach (array_keys($map[$y]) as $x) {
                    if ($map[$y][$x] == 3) {
                        $paddle = $x;
                    } elseif ($map[$y][$x] == 4) {
                        $ball = $x;
                    }
                }
            }

            return $ball <=> $paddle;
        });
        $c->run();
        while ($c->running || $this->getBlockTiles($map) > 0) {
            if ($c->pauseReason == 'input') {
                $joystick = $ballX <= 19 ? -1 : 1;
//                if ($ballX > $padX) {
//                    $joystick = $ballDirection;
//                } elseif ($ballX < $padX) {
//                    $joystick = $ballDirection;
//                } else {
//                    $joystick = 0;
//                }
                $c->input($joystick);
                $c->run();
            }
            if ($c->pauseReason == 'output') {
                $x = $c->output;
                $c->run();
                $y = $c->output;
                $c->run();
                $type = $c->output;
                $c->run();

                if ($x == -1 && $y == 0) {
                    $score = $type;
                }

                if ($type == 4) {
                    if ($ballX > $x) {
                        $ballDirection = -1;
                    } elseif ($ballX < $x = -1) {
                        $ballDirection = 1;
                    } else {
                        $ballDirection = 0;
                    }
                    $ballX = $x;
                }
                if ($type == 3) {
                    $padX = $x;
                }
//                printf("%d,%d: %d\n", $x, $y, $type);
                $map[$y][$x] = $type;

                if ($this->getBlockTiles($map) > 0 && $c->running == false) {
                    $this->printMap($map, $score);
                    $c->reset(true);
                }
            }
        }

        $this->printMap($map);

        return $score;
    }

    protected function printMap($map, $score = null)
    {
        foreach (array_keys($map) as $y) {

            foreach (array_keys($map[$y]) as $x) {
                // 0 is an empty tile. No game object appears in this tile.
                //1 is a wall tile. Walls are indestructible barriers.
                //2 is a block tile. Blocks can be broken by the ball.
                //3 is a horizontal paddle tile. The paddle is indestructible.
                //4 is a ball tile. The ball moves diagonally and bounces off objects.
                $out = ' ';
                switch ($map[$y][$x]) {
                    case 1:
                        $out = '#';
                        break;
                    case 2:
                        $out = '@';
                        break;
                    case 3:
                        $out = '-';
                        break;
                    case 4:
                        $out = 'o';
                        break;
                    default:
                        $out = ' ';
                }
                if ($x !== -1) {
                    printf("%s", $out);
                }
            }
            printf("\n");
        }
        if ($score !== null) {
            printf("Score: %d\n", $score);
        }
    }
}