<?php

namespace App\Command;

use App\Day13\Arcade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day13Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day13:blocktiles')
            ->setDescription('Day 13 / part 1')
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

        $robot = new Arcade($data);
        $out = $robot->getBlockTiles();

        $output->writeln(sprintf(
            "Block tiles: %d",
            $out
        ));

        //
    }
}