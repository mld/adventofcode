<?php

namespace App\Command;

use App\Day04\Passports;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day04Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('4:2')
            ->setDescription('Day 4 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $pp = new Passports($contents);
        $output->writeln(sprintf("%d valid passports",$pp->validPassports(false)));
        return 0;
    }
    /// 226 too high
}
