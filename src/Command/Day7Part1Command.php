<?php

namespace App\Command;

use App\Day07\Amplifier;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day7Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day7:amp')
            ->setDescription('Day 7 / part 1')
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

        $maxOutput = 0;
        $bestSettings = [];

        for ($a = 0; $a <= 4; $a++) {
            for ($b = 0; $b <= 4; $b++) {
                for ($c = 0; $c <= 4; $c++) {
                    for ($d = 0; $d <= 4; $d++) {
                        for ($e = 0; $e <= 4; $e++) {
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