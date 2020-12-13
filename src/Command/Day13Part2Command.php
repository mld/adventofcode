<?php

namespace App\Command;

use App\Day13\ShuttleSearch;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day13Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('13:2')
            ->setDescription('Day 13 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $ss = new ShuttleSearch($contents);
        $output->writeln(sprintf("Gold coin timestamp: %d", $ss->part2()));
        // 1 024 220 991 808 692 too high
        // 100 000 000 000 000
        return 0;
    }
}
