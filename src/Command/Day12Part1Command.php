<?php

namespace App\Command;

use App\Day12\System;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day12Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day12:energy')
            ->setDescription('Day 12 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $system = new System($contents);
        $sum = $system->totalEnergy(1000);
//        $sum = $map->findBestAsteroid();

        $output->writeln('System Energy: ' . $sum);
//        $output->writeln('Best asteroid: ' . $sum[0]);
    }
}