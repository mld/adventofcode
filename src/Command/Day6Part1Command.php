<?php

namespace App\Command;

use App\Day06\UniversalOrbitMap;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day6Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day6:orbits')
            ->setDescription('Day 6 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        // Todo: code
        $uom = new UniversalOrbitMap($contents, true);
        $sum = $uom->orbitCounter();
        $output->writeln('Orbits: ' . $sum);
    }
}