<?php

namespace App\Command;

use App\Day07\HandyHaversacks;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day07Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('7:1')
            ->setDescription('Day 7 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $hh = new HandyHaversacks($contents);
        $count = $hh->bagsThatMayContainBag('shiny gold');
        $output->writeln(sprintf('Found %d bags that could contain at least one shiny gold bag.', $count));
        return 0;
    }
}
