<?php

namespace App\Command;

use App\Day03\TobogganTrajectory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day03Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('3:1')
            ->setDescription('Day 3 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $tt = new TobogganTrajectory($contents);
        $tt->printMap();
        $trees = $tt->traverseMap();
        $output->writeln(sprintf("Found %d trees", $trees));
        return 0;
    }
}
