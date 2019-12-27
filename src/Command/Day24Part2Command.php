<?php

namespace App\Command;

use App\Day24\Eris3D;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day24Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day24:bugs2')
            ->setDescription('Day 24 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $output->writeln('Creating Eris3d');
        $eris = new Eris3D();

        $output->writeln('Importing map');
        $eris->parseRaw($contents);
        $eris->printMap();

        $output->writeln('Run ticks');
        $eris->runTicks(201);
//        $eris->printMap();

        $output->writeln(sprintf(
            "Bugs: %s", $eris->countBugs()
        ));

        // 1928
    }
}