<?php

namespace App\Command;

use App\Day12\System;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day12Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day12:repeat')
            ->setDescription('Day 12 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $step = 0;
        // init state
        $system = new System($contents);
        $periodic = [-1, -1, -1];
        while (true) {
            $system->step(1);

            for ($dimension = 0; $dimension < 3; ++$dimension) {
                if ($periodic[$dimension] != -1) {
                    // This one's found already
                    continue;
                }

                $periodicByDimension = true;

                foreach (array_keys($system->moons) as $moon) {
                    $periodicByDimension &= $system->moons[$moon]->circular($dimension);
                }

                if ($periodicByDimension) {
                    $periodic[$dimension] = $step + 1;
                }
            }

            if ($periodic[0] != -1 && $periodic[1] != -1 && $periodic[2] != -1) {
                break;
            }

            $step++;
        }

        $sum = $system->totalEnergy();

        $step = $this->lcm($periodic[0], $this->lcm($periodic[1], $periodic[2]));
        $output->writeln('Step: ' . $step);
        $output->writeln('System Energy: ' . $sum);
    }

    public function lcm($a, $b)
    {
        return ($a * $b) / $this->gcd($a, $b);
    }

    public function gcd($a, $b)
    {
        if ($a == 0) {
            return $b;
        }
        return $this->gcd($b % $a, $a);
    }
}