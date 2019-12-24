<?php

namespace App\Command;

use App\Day21\SpringDroid;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day21Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day21:walk')
            ->setDescription('Day 21 / part 1')
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

        $ascii = new SpringDroid($data);
//        $ascii->createMap();

        $out = $ascii->hullDamage();

        $output->writeln(sprintf(
            "Hull damage: %s",$out
        ));
    }
}