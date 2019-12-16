<?php

namespace App\Command;

use App\Day15\RepairDroid;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day15Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day15:oxygensystem')
            ->setDescription('Day 15 / part 1')
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

        $robot = new RepairDroid($data);
        $out = $robot->run(false);

//        $output->writeln(sprintf(
//            "Steps: %d (but really, 248)",
//            $out
//        ));

        //
    }
}