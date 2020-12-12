<?php

namespace App\Command;

use App\Day11\SeatingSystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day11Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('11:1')
            ->setDescription('Day 11 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $ss = new SeatingSystem($contents);
        $output->writeln(sprintf('Found %d occupied seats', $ss->part1()));
        return 0;
    }
}
