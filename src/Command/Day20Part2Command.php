<?php

namespace App\Command;

use App\Day20\PortalMaze;
use App\Day20\PortalMaze3D;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day20Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day20:3dsteps')
            ->setDescription('Day 20 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'), "\n\r");

        $maze = new PortalMaze3D($contents, true);
        $maze->parseMaze();
        $map = [];
        $stepMap = [];
//        $steps = $maze->portalPath2d('AA', 'ZZ', 0, $stepMap);
        $steps = $maze->portalPath3d('AA', 'ZZ', 0,0, $stepMap);
//        $maze->printMap($map,$stepMap);
//        print_r($stepMap);
        $output->writeln('Out: ' . $steps);
        // 528 too low
        // 6214 - just right
    }
}