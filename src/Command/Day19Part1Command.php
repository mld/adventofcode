<?php

namespace App\Command;

use App\Day19\TractorBeam;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day19Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day19:scan')
            ->setDescription('Day 19 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $beam = new TractorBeam($contents[0], 50, 50);
        $out = $beam->scan();
        $beam->printField();
        $output->writeln('Out: ' . $out);
    }
}