<?php

namespace App\Command;

use App\Day22\CrabCombat;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day22Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('22:2')
            ->setDescription('Day 22 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);
        $contents = $this->parseFiles($input->getArgument('filename'));
        $mm = new CrabCombat($contents);
        $output->writeln(sprintf(
            "%s: %d (%.3fs)",
            $this->getName(),
            $mm->part2(),
            (microtime(true) - $startTime)
        ));
        return 0;
    }
}
