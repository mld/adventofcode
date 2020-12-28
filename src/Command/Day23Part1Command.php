<?php

namespace App\Command;

use App\Day23\CrabCups;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day23Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('23:1')
            ->setDescription('Day 23 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);
        $contents = $this->parseFiles($input->getArgument('filename'));
        $mm = new CrabCups($contents);
        $output->writeln(sprintf(
            "%s: %d (%.3fs)",
            $this->getName(),
            $mm->part1(),
            (microtime(true) - $startTime)
        ));
        return 0;
    }
}
