<?php

namespace App\Command;

use App\Day06\UniversalOrbitMap;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day6Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day6:shortestPath')
            ->setDescription('Day 6 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        // Todo: code
//        $output->writeln('Code: ' . $sum);
        $uom = new UniversalOrbitMap($contents, true);
        $sum = $uom->shortestPath();
        $output->writeln('Steps: ' . $sum);
    }
}