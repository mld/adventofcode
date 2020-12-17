<?php

namespace App\Command;

use App\Day17\ConwayCubes;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day17Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('17:2')
            ->setDescription('Day 17 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $cc = new ConwayCubes($contents);
        $output->writeln(sprintf("Part 2: %d", $cc->part2()));
        return 0;
    }
}
