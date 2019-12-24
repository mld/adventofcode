<?php

namespace App\Command;

use App\Day23\Network;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day23Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day23:nat')
            ->setDescription('Day 23 / part 2')
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

        $net = new Network($data,50,false);

        $out = 'oops!';
        $out = $net->nat();

        $output->writeln("");
        $output->writeln(sprintf(
            "First double Y from 255: %s",var_export($out,true)
        ));
    }
    // 19643 too high
}