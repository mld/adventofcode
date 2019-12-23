<?php

namespace App\Command;

use App\Day22\Stack;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day22Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day22:shuffle')
            ->setDescription('Day 22 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $deck = new Stack($contents);
        $deck->shuffleByInstructions();

        $out = $deck->find(2019);
        $output->writeln(sprintf(
            "Card 2019 is found as number: %s", var_export($out, true)
        ));
    }

    //1778 too low
}