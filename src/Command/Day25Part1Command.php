<?php

namespace App\Command;

use App\Day13\Computer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Day25Part1Command extends FileInputCommand
{
    protected function configure()
    {
        $this
            ->setName('day25:mud')
            ->setDescription('Day 25 / part 1')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Enter command: ');
        $question->setNormalizer(function ($value) {
            // $value can be null here
            return $value ? trim($value) : '';
        });
        $autoCompleteWords = [
            'north',
            'south',
            'east',
            'west',
            'take',
            'drop',
            'inv',
            'stop',
            'take manifold',
            'drop manifold',
            'take whirled peas',
            'drop whirled peas',
            'take antenna',
            'drop antenna',
            'take bowl of rice',
            'drop bowl of rice',
            'take klein bottle',
            'drop klein bottle',
            'take spool of cat6',
            'drop spool of cat6',
        ];
        $question->setAutocompleterValues($autoCompleteWords);
        $question->setValidator(function ($answer) use ($autoCompleteWords) {
            $words = explode(' ', $answer);
            if (!is_string($answer) || !in_array($words[0], $autoCompleteWords)) {
                throw new \RuntimeException(
                    $answer . ' is not a valid command'
                );
            }

            return $answer;
        });
        $question->setMaxAttempts(null);

        $contents = $this->parseFiles($input->getArgument('filename'));
        $data = [];
        foreach ($contents as $row) {
            $opcodes = explode(',', $row);
            $data = array_merge($data, $opcodes);
        }

        $computer = new Computer($data, true, false);
        $history = [];
        $log = [];
        $logPos = 0;
        $inputs = [];
        $last = null;
        $running = true;
        $linebreaks = 0;
        do {
            if ($computer->pauseReason == 'output') {
//                if (chr($computer->output) == '.') {
//                    printf(" ");
//                } else {
                printf("%c", $computer->output);
//                }
                if (ord($computer->output) == 10) {
                    $linebreaks++;
                    $logPos++;
                } else {
                    $linebreaks = 0;
                    $log[$logPos] = sprintf($computer->output);
                }
                $computer->run();

            }
            if ($computer->pauseReason == 'input') {
                $character = array_shift($inputs);
                if ($character !== null) {
                    $computer->input(ord($character));
                } else {
                    // Require input from user
//                    $validInput = false;
                    $response = $helper->ask($input, $output, $question);
                    $output->writeln('Response: ' . $response);
                    $history[] = $response;
                    $words = explode(' ', $response);
                    do {
                        $validInput = false;
                        switch ($words[0]) {
                            case 'north':
                            case 'south':
                            case 'east':
                            case 'west':
                            case 'inv':
                                $inputs = array_merge($inputs, str_split($words[0]));
                                $inputs[] = chr(10);
                                $validInput = true;
                                break;
                            case 'take':
                                if (in_array($response,
                                    [
                                        'take photons',
                                        'take giant electromagnet',
                                        'take molten lava',
                                        'take infinite loop',
                                        'take dark matter',
                                        'take escape pod'
                                    ])) {
                                    break 2;
                                }
                            case 'drop':
                                $inputs = array_merge($inputs, str_split($response));
                                $inputs[] = chr(10);
                                $validInput = true;
                                break;
                            case 'stop':
                                $output->writeln('History:');
                                foreach ($history as $entry) {
                                    $output->writeln('  ' . $entry);
                                }
                                $running = false;
                                $validInput = true;
                        }
                    } while (!$validInput);
                    $character = array_shift($inputs);
                    $computer->input(ord($character));
                }
            }
        } while ($running == true && $linebreaks < 10);

        return $computer->output;

//        $output->writeln(sprintf(
//            "Biodiversity duplicate: %s",var_export($out,true)
//        ));
    }
}

// nope-list:
// - whirled peas
// -