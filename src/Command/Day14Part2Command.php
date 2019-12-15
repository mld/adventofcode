<?php

namespace App\Command;

use App\Day14\NanoFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day14Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day14:opf')
            ->setDescription('Day 14 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $f = new NanoFactory($contents);

        $target = 1000000000000;
        $waste = [];
        $oresPerFuel = $f->oresRequired('FUEL', 1, $waste);
        $testFuel = ceil($target / ($oresPerFuel));

        $output->writeln("Ores required for 1 fuel: " . $oresPerFuel . " and waste: " . var_export($waste, true));

        while ($f->oresRequired('FUEL', $testFuel + 1) < $target) {
            $waste = [];
            $ores = $f->oresRequired('FUEL', $testFuel, $waste);
            $output->writeln("Ores required for " . $testFuel . " fuel: " . $ores . ' with waste: ' . var_export($waste,
                    true));

            $direction = $target <=> $ores;
            $testFuel += intdiv(($target - $ores), $oresPerFuel) * $direction;
            $output->writeln("Fuel/Ores/Dir: " . $testFuel . '/' . $ores . '/' . $direction);
        }

        $out = $f->oresRequired('FUEL', $testFuel);
        $output->writeln(sprintf(
            "%d fuel produced with %d ores", $testFuel,
            $out
        ));
    }
}