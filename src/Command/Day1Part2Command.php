<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2018-12-03
 * Time: 19:22
 */

namespace App\Command;

use App\Day1First;
use App\Day1;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day1Part2Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('day1:duplicate')
            ->setDescription('Day 1 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = [];
        if ($filename = $input->getArgument('filename')) {
            $contents = file($filename);
        } else {
            if (0 === ftell(STDIN)) {
                while ($row = fgets(STDIN)) {
                    $contents[] = $row;
                }
            } else {
                throw new \RuntimeException("Please provide a filename or pipe content to STDIN.");
            }
        }


        $result = Day1::getDuplicate($contents);

        $output->writeln('Twice: ' . $result);
    }
}
