<?php

namespace App\Command;

use App\Day07\HandyHaversacks;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day07Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('7:2')
            ->setDescription('Day 7 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $hh = new HandyHaversacks($contents);
        $count = $hh->bagsInBag('shiny gold');
        $output->writeln(sprintf('A single shiny gold bag must contain %s other bags.', $count));

        return 0;
    }
}
