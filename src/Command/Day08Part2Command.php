<?php

namespace App\Command;

use App\Day08\HandheldHalting;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day08Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('8:2')
            ->setDescription('Day 8 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $hh = new HandheldHalting($contents);
        $output->writeln("Accumulator value when ending: " . $hh->switchOperators());
        return 0;
    }
}
