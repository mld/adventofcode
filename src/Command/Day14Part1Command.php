<?php

namespace App\Command;

use App\Day14\DockingData;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day14Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('14:1')
            ->setDescription('Day 14 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $dd = new DockingData($contents);
        $output->writeln(sprintf("Part 1: %d", $dd->part1()));
        return 0;
    }
}
