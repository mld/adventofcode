<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2018-12-03
 * Time: 19:22
 */

namespace App\Command;

use App\Day03\Wires;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day3Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day3:distance')
            ->setDescription('Day 3 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $wireMap = new Wires($contents[0], $contents[1]);
        $distance = $wireMap->findClosestCrossing(Wires::MANHATTAN_DISTANCE);

        $output->writeln('Distance: ' . $distance);
    }
}
