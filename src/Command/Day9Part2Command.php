<?php

namespace App\Command;

use App\Day09\StateMachine;
use App\Day11\Computer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day9Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day9:boost')
            ->setDescription('Day 9 / part 2')
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

        $m = new StateMachine($data, false);
        $input = 2;
        while (!$m->isTerminated()) {
            $sum = $m->run($input);
            $input = [];
        }


        $m = new Computer(join(',',$data),true,false);
        $m->input(2);
        $sum2 = $m->output;

        $output->writeln('Code: ' . $sum . ' : ' . $sum2);

        $output->writeln('Code: ' . $sum);
    }
}