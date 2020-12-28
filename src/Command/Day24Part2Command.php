<?php

namespace App\Command;

use App\Day24\LobbyLayout;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day24Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('24:2')
            ->setDescription('Day 24 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);
        $contents = $this->parseFiles($input->getArgument('filename'));
        $mm = new LobbyLayout($contents);
        $output->writeln(sprintf(
            "%s: %d (%.3fs)",
            $this->getName(),
            $mm->part2(),
            (microtime(true) - $startTime)
        ));
        return 0;
    }
}
