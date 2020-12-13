<?php

namespace App\Command;

use App\Day13\ShuttleSearch;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day13Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('13:1')
            ->setDescription('Day 13 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $ss = new ShuttleSearch($contents);
        $output->writeln(sprintf("ID * wait in minutes: %d", $ss->part1()));
        return 0;
    }
}
