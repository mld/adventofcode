<?php

namespace App\Command;

use App\Day05\StateMachine;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day5Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day5:radiator')
            ->setDescription('Day 5 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $data = [];
        foreach($contents as $row) {
            $opcodes = explode(',',$row);
            $data = array_merge($data,$opcodes);
        }

        $m = new StateMachine($data,true);
        $sum = $m->run(5);

        $output->writeln('Code: ' . $sum);
    }
}