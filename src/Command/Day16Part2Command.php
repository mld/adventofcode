<?php

namespace App\Command;

use App\Day15\RepairDroid;
use App\Day16\FlawedFrequencyTransmission;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day16Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day16:ffthuge')
            ->setDescription('Day 16 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $data = str_split(trim($contents[0]));

        $f = new FlawedFrequencyTransmission($data);

        $out = $f->part2();

        $output->writeln(sprintf("Out:  %s", $out));
    }
}