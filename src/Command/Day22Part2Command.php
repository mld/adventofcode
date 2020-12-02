<?php

namespace App\Command;

use App\Day22\Stack;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day22Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day22:megashuffle')
            ->setDescription('Day 22 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $out = Stack::advancedShuffle($contents, 2020, 119315717514047, 101741582076661);
        $output->writeln(sprintf(
            "Card 2020 is found as number: %s", var_export($out, true)
        ));
    }

    //1778 too low
    //58261329434148 too high
    //1644352419829 - just right, but needs gmp for php
}