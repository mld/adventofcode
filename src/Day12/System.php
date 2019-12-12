<?php


namespace App\Day12;


class System
{
    public $raw;
    public $moons;
    public $step;

    public function __construct($input)
    {
        $this->step = 0;
        $this->raw = $input;
        $this->moons = [];
        $this->parseInput();
        unset($this->raw);
    }

    public function __toString()
    {
        $out = [];
        foreach ($this->moons as $a) {
            $out[] = (string)$a;
        }
        return join(' ', $out);
    }

    public function parseInput()
    {
        if (!is_array($this->raw)) {
            $this->raw = explode("\n", $this->raw);
        }
        $n = 0;
        foreach ($this->raw as $line) {
            [$x, $y, $z] = sscanf($line, '<x=%d, y=%d, z=%d>');
            $this->moons[] = new Moon($x, $y, $z);
//            printf("%d: x: %d, y: %d, z: %d\n", $n, $x, $y, $z);
        }

    }

    public function step($steps = 0)
    {
        for ($n = 0; $n < $steps; $n++) {
            /** @var Moon $a */
            foreach ($this->moons as $a) {
                /** @var Moon $b */
                foreach ($this->moons as $b) {
                    if (Moon::eq($a, $b)) {
                        // We don't affect ourselves
                        continue;
                    }
                    $a->applyGravity($b);
                }
            }

            foreach ($this->moons as $a) {
                $a->applyVelocity();
//                    printf("%04d: %s\n", $n + $this->step + 1, trim($a));
            }
//            printf("%d\n", $this->step);
        }

        $this->step += $steps;
    }

    public function totalEnergy($verbose = true)
    {
        if ($verbose) {
            foreach ($this->moons as $a) {
                printf("%04d: %s\n", $this->step - 1, trim($a));
            }
            printf("-\n");
        }

        $sum = 0;
        foreach ($this->moons as $a) {
            $sum += $a->totalEnergy();
        }
        return $sum;
    }
}