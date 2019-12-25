<?php

namespace App\Command;

use App\Day24\Eris;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day24Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day24:bugs')
            ->setDescription('Day 24 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $eris = new Eris($contents);
        $out = $eris->findDuplicate();
        $output->writeln(sprintf(
            "Biodiversity duplicate: %s",var_export($out,true)
        ));
    }
}