<?php

namespace App\Command;

use App\Day17\Ascii;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day17Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day17:dust')
            ->setDescription('Day 17 / part 2')
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
        $out = $ascii->dust();

        $output->writeln(sprintf(
            "Dust collected: %d",
            $out
        ));

        // not 809736
    }
}