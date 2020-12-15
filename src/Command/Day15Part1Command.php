<?php

namespace App\Command;

use App\Day15\RambuctiousRecitation;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day15Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('15:1')
            ->setDescription('Day 15 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $rr = new RambuctiousRecitation($contents);
        $output->writeln(sprintf("Part 1: %d", $rr->part1()));
        return 0;
    }
}
