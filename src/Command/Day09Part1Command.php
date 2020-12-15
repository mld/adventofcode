<?php

namespace App\Command;

use App\Day09\EncodingError;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Day09Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('9:1')
            ->setDescription('Day 9 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->addOption('buffer', 'b', InputOption::VALUE_REQUIRED, 'Input to script.', 25)
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $buffer = intval($input->getOption('buffer'));
        $output->writeln(sprintf('Buffer size: %d', $buffer));
        $ee = new EncodingError($contents, $buffer);
        $invalid = $ee->findFirstInvalid();
        $output->writeln(sprintf('First invalid value found: %d', $invalid));
        return 0;
    }
}
