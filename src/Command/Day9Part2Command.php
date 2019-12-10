<?php

namespace App\Command;

use App\Day09\StateMachine;
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

        $m = new StateMachine($data, true);
        $input = 2;
        while (!$m->isTerminated()) {
            $sum = $m->run($input);
            $input = [];
        }

        $output->writeln('Code: ' . $sum);
    }
}