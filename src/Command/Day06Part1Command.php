<?php

namespace App\Command;

use App\Day06\CustomsDeclarationForms;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day06Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('6:1')
            ->setDescription('Day 6 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $cdf = new CustomsDeclarationForms($contents);
        $output->writeln('Sum of distinct yes per group: ' . $cdf->sumDistinctYesPerGroup());
        return 0;
    }
}
