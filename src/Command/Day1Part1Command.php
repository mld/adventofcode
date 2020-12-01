<?php

/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2018-12-03
 * Time: 19:22
 */

namespace App\Command;

use App\Day01\ExpenseReport;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day1Part1Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('1:1')
            ->setDescription('Day 1 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $expense = new ExpenseReport($contents);
        $rows = $expense->getRowsBySum(2020);
        if (count($rows) == 2) {
            $output->writeln('Sum: ' . ($rows[0] * $rows[1]));
        } else {
            $output->writeln('No rows returned.');
        }

        return 0;
    }
}
