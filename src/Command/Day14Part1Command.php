<?php

namespace App\Command;

use App\Day14\NanoFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day14Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day14:nanofactory')
            ->setDescription('Day 14 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $f = new NanoFactory($contents);

        $out = $f->oresRequired('FUEL',1);

        $output->writeln(sprintf(
            "Ore required for producing 1 fuel: %d",
            $out
        ));
    }
}