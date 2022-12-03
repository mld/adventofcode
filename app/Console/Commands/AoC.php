<?php

namespace App\Console\Commands;

use App\Solution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AoC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aoc {year} {day} {part} {file=input}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the year/day/part AoC solution';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $year = (int)$this->argument('year');
        $day = (int)$this->argument('day');
        $part = (int)$this->argument('part');
        $file = (string)$this->argument('file');
        $input = Solution::inputFilename($year, $day, $file);
        $answers[] = Solution::inputFilename($year, $day, $file . '.part' . $part . '.answer');
        $answers[] = Solution::inputFilename($year, $day, $file . '.answer');

        // Make sure the input file is where it's supposed to
        if (!Storage::exists($input)) {
            $this->error(sprintf('Input file not found. %s/%s is missing.', storage_path('app'), $input));
            return 1;
        };

        // default class name should be prepended with 0
        $solutionName = sprintf('\\App\\Solutions\\_%d\\_%02d', $year, $day);
        if (!class_exists($solutionName)) {
            $solutionName = sprintf('\\App\\Solutions\\_%d\\_%d', $year, $day);
        }

        if (!class_exists($solutionName)) {
            $this->error('No such solution found. Create one with aoc:make {year} {day}');
            return 1;
        }

        /** @var Solution $solution */
        $solution = new $solutionName(['year' => $year, 'day' => $day, 'file' => $file]);

        // time it!
        $start = microtime(true);
        $result = $solution->solve($part);
        $end = microtime(true);

        $this->line(sprintf(
                'Solution for %d/%02d/%d found in %.3f seconds: ',
                $year,
                $day,
                $part,
                ($end - $start)
            )
        );
        $this->line("\t" . $result);

        foreach ($answers as $answer) {
            if (Storage::exists($answer)) {
                $this->line(" =\t" . trim(Storage::get($answer)));
                break;
            }
        }
        return 0;
    }
}
