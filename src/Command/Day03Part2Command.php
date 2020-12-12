<?php

namespace App\Command;

use App\Day03\TobogganTrajectory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day03Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('3:2')
            ->setDescription('Day 3 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $tt = new TobogganTrajectory($contents);
        $tt->printMap();
        $fix = [];

        $trees = $tt->traverseMap(1, 1);
        $fix[] = $trees;
        $output->writeln(sprintf("Found %d trees", $trees));

        $trees = $tt->traverseMap(3, 1);
        $fix[] = $trees;
        $output->writeln(sprintf("Found %d trees", $trees));

        $trees = $tt->traverseMap(5, 1);
        $fix[] = $trees;
        $output->writeln(sprintf("Found %d trees", $trees));

        $trees = $tt->traverseMap(7, 1);
        $fix[] = $trees;
        $output->writeln(sprintf("Found %d trees", $trees));

        $trees = $tt->traverseMap(1, 2);
        $fix[] = $trees;
        $output->writeln(sprintf("Found %d trees", $trees));

        $sum = array_product($fix);
        $output->writeln(sprintf("Answer %d", $sum));
        return 0;
    }
}
