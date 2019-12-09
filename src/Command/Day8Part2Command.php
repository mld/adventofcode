<?php

namespace App\Command;

use App\Day08\SpaceImageFormat;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day8Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day8:sifmessage')
            ->setDescription('Day 8 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));
        $sif = new SpaceImageFormat($contents[0], 6, 25);
        $sif->printImage();
    }
}