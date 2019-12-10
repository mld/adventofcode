<?php

namespace App\Command;

use App\Day10\AsteroidMap;
use App\Day10\Point;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day10Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day10:vaporizer')
            ->setDescription('Day 10 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $map = new AsteroidMap(join("\n", $contents));
        list($last, $n) = $map->vaporizer(200);

        $sum = $last->x * 100 + $last->y;
        // what do you get if you multiply its X coordinate by 100 and then add its Y coordinate? (For example, 8,2 becomes 802.)
        $output->writeln($n . ' lazered asteroids');
        $output->writeln('Last lasered asteroid: ' . $last);
        $output->writeln('Sum: ' . $sum);

        // 2713 too high
    }
}