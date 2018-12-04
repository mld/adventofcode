<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2018-12-03
 * Time: 19:22
 */

namespace App\Command;

use App\Day1;
use App\Day1First;
use App\Day2;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day4Part2Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('day4:strategy2')
            ->setDescription('Day 4 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = [];
        if ($filenames = $input->getArgument('filename')) {
            if (!is_array($filenames)) {
                $filenames = [$filenames];
            }
            foreach ($filenames as $filename) {
                $contents = file($filename);
            }
        } else {
            if (0 === ftell(STDIN)) {
                while ($row = fgets(STDIN)) {
                    $contents[] = trim($row);
                }
            } else {
                throw new \RuntimeException("Please provide a filename or pipe content to STDIN.");
            }
        }

        $sum = \App\Day4::strategy2($contents);

        $output->writeln('Guard ID * Minute: ' . $sum);
    }
}
