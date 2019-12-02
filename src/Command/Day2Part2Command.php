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
use App\Day2\StateMachine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day2Part2Command extends Command
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
