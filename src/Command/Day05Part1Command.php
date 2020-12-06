<?php

namespace App\Command;

use App\Day05\BoardingPass;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day05Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('5:1')
            ->setDescription('Day 5 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $bp = new BoardingPass($contents);
        $output->writeln(sprintf('Highest seat ID found: %d', $bp->getHighestSeatId()));
        return 0;
    }
}
