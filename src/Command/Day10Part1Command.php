<?php

namespace App\Command;

use App\Day10\AdapterArray;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day10Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('10:1')
            ->setDescription('Day 10 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $aa = new AdapterArray($contents);
        $val = $aa->part1();
        $output->writeln(sprintf("returned %d", $val));
        return 0;
    }
}
