<?php

namespace App\Command;

use App\Day21\AllergenAssessment;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day21Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('21:2')
            ->setDescription('Day 21 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);
        $contents = $this->parseFiles($input->getArgument('filename'));
        $mm = new AllergenAssessment($contents);
        $output->writeln(sprintf(
            "%s: %s (%.3fs)",
            $this->getName(),
            $mm->part2(),
            (microtime(true) - $startTime)
        ));
        return 0;
    }
}
