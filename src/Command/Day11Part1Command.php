<?php

namespace App\Command;

use App\Day11\EmergencyHullPaintingRobot;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day11Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day11:panels')
            ->setDescription('Day 11 / part 1')
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

        $robot = new EmergencyHullPaintingRobot($data);
        $out = $robot->run();

        $output->writeln(sprintf(
            "Painted panels: %d",
            $out
        ));

        // 1256 too low
        // 2610 too high
        // 1313 not right
    }
}