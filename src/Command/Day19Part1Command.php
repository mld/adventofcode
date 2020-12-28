<?php

namespace App\Command;

use App\Day19\MonsterMessages;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day19Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('19:1')
            ->setDescription('Day 19 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $mm = new MonsterMessages($contents);
        $output->writeln(sprintf("%s: %d", $this->getName(), $mm->part1()));
        return 0;
    }
}
