<?php

namespace App\Command;

use App\Day09\StateMachine;
use App\Day11\Computer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day9Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day9:boostCheck')
            ->setDescription('Day 9 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $data = [];
        foreach ($contents as $row) {
            $opcodes = explode(',', $row);
            $data = array_merge($data, $opcodes);
        }

        $m = new StateMachine($data, true);
        $input = 1;
//        $input = [];
        while (!$m->isTerminated()) {
            $sum = $m->run($input);
            $input = [];
        }
        // 0 - not it
        // 203 - too low

        $m = new Computer(join(',',$data),true,true);
        $m->input(1);
        $sum2 = $m->output;

        $output->writeln('Code: ' . $sum . ' : ' . $sum2);
    }
}