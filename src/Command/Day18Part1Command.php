<?php

namespace App\Command;

use App\Day18\OperationOrder;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day18Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('18:1')
            ->setDescription('Day 18 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $oo = new OperationOrder($contents);
        $output->writeln(sprintf("Part 1: %d", $oo->part1()));
        return 0;
    }
}
