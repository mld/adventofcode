<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2018-12-03
 * Time: 19:22
 */

namespace App\Command;

use App\Day01\FuelCounterUpper;
use App\Day01\Module;
use App\Day1;
use App\Day1First;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day1Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day1:fuelCounterUpperAdvanced')
            ->setDescription('Day 1 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $modules = [];
        foreach ($contents as $row) {
            $modules[] = new Module(['mass' => $row]);
        }
        $fcu = new FuelCounterUpper($modules);
        $sum = $fcu->getFuelRequirement(false);

        $output->writeln('Sum: ' . $sum);
    }
}
