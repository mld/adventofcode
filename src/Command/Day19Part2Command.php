<?php

namespace App\Command;

use App\Day19\TractorBeam;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day19Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day19:range')
            ->setDescription('Day 19 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $beam = new TractorBeam($contents[0], 50, 50);
        $topleft = $beam->range(100,100);
//        $beam->printField();
        $out = $topleft->x*10000+$topleft->y;
        $output->writeln('Out: ' . $out);
    }
}