<?php

namespace App\Command;

use App\Day07\Amplifier;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day7Part2Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day7:ampfeedback')
            ->setDescription('Day 7 / part 2')
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

        $maxOutput = PHP_INT_MIN;
        $bestSettings = [];

        for ($a = 5; $a <= 9; $a++) {
            for ($b = 5; $b <= 9; $b++) {
                for ($c = 5; $c <= 9; $c++) {
                    for ($d = 5; $d <= 9; $d++) {
                        for ($e = 5; $e <= 9; $e++) {
                            $in = [$a, $b, $c, $d, $e];
                            if (count(array_unique($in)) != 5) {
                                // the inputs may only be used once for each set of instruction
                                continue;
                            }
                            $amp = new Amplifier($data, false);
                            $out = $amp->run($in);
                            if ($out > $maxOutput) {
                                $maxOutput = $out;
                                $bestSettings = array_merge($in);
                            }

                            $output->writeln(sprintf(
                                "%s: %d. Max: %d (%s)",
                                join('', $in),
                                $out,
                                $maxOutput,
                                join('', $bestSettings)
                            ));

                        }
                    }
                }
            }
        }

        $output->writeln(sprintf(
            "Max Output Signal: %d (%s)",
            $maxOutput,
            join('', $bestSettings)
        ));
    }
}