<?php

namespace App\Command;

use App\Day15\RambuctiousRecitation;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day15Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('15:2')
            ->setDescription('Day 15 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $rr = new RambuctiousRecitation($contents);
        $output->writeln(sprintf("Part 2: %d", $rr->part2()));
        return 0;
    }
}
