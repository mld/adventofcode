<?php

namespace App\Command;

use App\Day12\RainRisk;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day12Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('12:2')
            ->setDescription('Day 12 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $rr = new RainRisk($contents);
        $output->writeln(sprintf("Manhattan distance when all commands are fulfilled is %d", $rr->part2()));
        return 0;
    }
}
