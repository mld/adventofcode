<?php


namespace App\Day10;


class AsteroidMap
{
    public $raw;
    public $debug;
    public $asteroids;

    public function __construct($raw, $debug = false)
    {
        $this->debug = $debug;
        $this->asteroids = [];

        if (file_exists($raw)) {
            $this->parseRaw(file_get_contents($raw));
        } else {
            $this->parseRaw($raw);
        }
    }

    public function parseRaw($raw)
    {
        $this->raw = $raw;

//        printf("\n%s\n", print_r($raw, true));
        $rows = explode("\n", trim($raw));
        foreach ($rows as $y => $row) {
            $cols = str_split(trim($row));

            foreach ($cols as $x => $item) {
                if ($item == '#') {
                    $this->asteroids[] = new Point($x, $y);
                }
            }
        }

//        foreach ($this->asteroids as $origin) {
//            foreach ($this->asteroids as $asteroid) {
//                $angle = rad2deg(atan2($asteroid->getY() - $origin->getY(), $asteroid->getX() - $origin->getX()));
//                printf("%s -> %s: %.2f\n", $origin, $asteroid, $angle);
//            }
//        }
    }

    public function findBestAsteroid()
    {
        $result = [];
        $best = null;

        foreach ($this->asteroids as $origin) {
            $visible = [];
            foreach ($this->asteroids as $asteroid) {
                if (Point::eq($origin, $asteroid)) {
                    // ignore ourselves
                    continue;
                }
                $angle = Point::angle($origin, $asteroid);
                $visible[] = $angle;
            }

            if (count(array_unique($visible)) > count($result)) {
                $result = array_unique($visible);
                $best = $origin;
            }

//            printf("\n%s\n",print_r($visible,true));
//            printf("%s -> (x,y): %d (max: %d: %s)\n", $origin, count($visible),$maxVisible,$best);
        }

        return [$best, count($result)];
    }

    public function vaporizer($return = 0)
    {
        /** @var \App\Day10\Point $origin */
        $origin = $this->findBestAsteroid()[0];

        $visible = [];
        /** @var Point $asteroid */
        foreach ($this->asteroids as $asteroid) {
            if (Point::eq($asteroid, $origin)) {
                continue;
            }

            $angle = Point::angle($origin, $asteroid);
            $angle += 90;
            if ($angle < 0) {
                $angle += 360;
            }

            $visible["$angle"][] = $asteroid;
        }

        // Sort angles
        uksort($visible, function ($a, $b) {
            return (float)$a <=> (float)$b;
        });

        // Sort asteroids by distance for each angle
        foreach (array_keys($visible) as $angle) {
            usort($visible[$angle], function ($a, $b) use ($origin) {
                return (Point::distance($origin, $a) <=> Point::distance($origin, $b));
            });
        }

        $i = 1;
        while (true) {
            foreach (array_keys($visible) as $angle) {
                $current = array_shift($visible[$angle]);

                if ($i === $return) {
                    return [$current, $i];
                }
                $i++;
            }
        }
    }
}