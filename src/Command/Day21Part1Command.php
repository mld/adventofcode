<?php

namespace App\Command;

use App\Day17\Ascii;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day17Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day17:ascii')
            ->setDescription('Day 17 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $data = [];
        foreach ($contents as $row) {
            $opcodes = explode(',', $row);
            $data = array_merge($data, $opcodes);
        }

        $ascii = new Ascii($data);
        $ascii->createMap();

        $output->writeln(sprintf(
            "Calculate the sum of the product of all the crossing scaffolds"
        ));

        // 4220
    }
}