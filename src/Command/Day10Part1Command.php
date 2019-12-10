<?php

namespace App\Command;

use App\Day10\AsteroidMap;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day10Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day10:asteroid')
            ->setDescription('Day 10 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $map = new AsteroidMap(join("\n", $contents));
        $sum = $map->findBestAsteroid();

        $output->writeln('Visible asteroids: ' . $sum[1]);
        $output->writeln('Best asteroid: ' . $sum[0]);
    }
}