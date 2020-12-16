<?php

namespace App\Command;

use App\Day16\TicketTranslation;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day16Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('16:1')
            ->setDescription('Day 16 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $tt = new TicketTranslation($contents);
        $output->writeln(sprintf("Part 1: %d", $tt->part1()));
        return 0;
    }
}
