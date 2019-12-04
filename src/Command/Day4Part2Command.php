<?php

namespace App\Command;

use App\Day04\Passwords;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day4Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day4:pwcount2')
            ->setDescription('Day 4 / part 2')
            ->setDescription('Day 4 / part 1')
//            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $contents = $this->parseFiles($input->getArgument('filename'));

        $pw = new Passwords();
        $count = 0;
        for ($n = 387638; $n <= 919123; $n++ ) {
            $ok = $pw::DoubleAscendingNoTripleCheck($n);
//            printf("%s: %b\n", $n,$ok);
            if($ok) $count++;
        }
        $output->writeln('Count: ' . $count);
    }
}