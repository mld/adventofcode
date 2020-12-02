<?php

namespace App\Command;

use App\Day02\PasswordPolicy;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Day02Part2Command extends FileInputCommand
{
    protected function configure(): void
    {
        $this
            ->setName('2:2')
            ->setDescription('Day 2 / part 2')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Input to script.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contents = $this->parseFiles($input->getArgument('filename'));

        $pp = new PasswordPolicy($contents, true);
        $validPasswords = $pp->validPasswordCount();
        $output->writeln(sprintf('%d valid passwords in list.', $validPasswords));

        return 0;
    }
}
