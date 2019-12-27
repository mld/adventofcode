<?php

namespace App\Command;

use App\Day20\PortalMaze;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day20Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day20:steps')
            ->setDescription('Day 20 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'),"\n\r");

        $maze = new PortalMaze($contents, true);
        $maze->parseMaze();
        $map = [];
        $stepMap = [];
        $steps = $maze->shortestPathToExit($map,null,1,$stepMap);
//        $out = $beam->scan();
//        $beam->printField();
        $maze->printMap($map,$stepMap);
        $output->writeln('Out: ' . $steps);
    }
}