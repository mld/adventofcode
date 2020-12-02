<?php

namespace App\Command;

use App\Day18\Vault;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day18Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day18:keys')
            ->setDescription('Day 18 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $vault = new Vault($contents);
        $out = $vault->findKeys();
//        $robot->run(false);
        $output->writeln('Out: ' . $out);
// 5450
    }
}