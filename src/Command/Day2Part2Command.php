<?php
/**
 * Created by PhpStorm.
 * User: mld
 * Date: 2018-12-03
 * Time: 19:22
 */

namespace App\Command;

use App\Day02\StateMachine;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day2Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day2:find')
            ->setDescription('Day 2 / part 2')
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

        $dataOrig = $data;

        for ($verb = 0; $verb < 100; $verb++) {
            for ($noun = 0; $noun < 100; $noun++) {
                $data = $dataOrig;
                $data[1] = $noun;
                $data[2] = $verb;
                $m = new StateMachine($data);
                $sum = $m->run();

                if ($sum == 19690720) {
                    $output->writeln('100 * noun (' . $noun . ') + verb (' . $verb . ')= ' . (100 * $noun + $verb));
                    exit();
                }
            }
        }
        $data[1] = 12;
        $data[2] = 2;


        $output->writeln('Sum: ' . $sum);
    }
}
