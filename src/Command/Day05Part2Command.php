<?php

namespace App\Command;

use App\Day05\BoardingPass;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day05Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('5:2')
            ->setDescription('Day 5 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $bp = new BoardingPass($contents);
        $output->writeln(sprintf('Your seat ID is found: %d', $bp->findMissingSeat()));

        return 0;
    }
}
