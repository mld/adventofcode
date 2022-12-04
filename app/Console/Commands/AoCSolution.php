<?php

namespace App\Console\Commands;

use App\Solution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Pandoc\Pandoc;
use Symfony\Component\DomCrawler\Crawler;

class AoCSolution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aoc:make {year} {day} {--f|fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates /app/Solutions/_<year>/_<day>.php';

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
    public function handle()
    {

        $year = $this->argument('year');
        $day = $this->argument('day');
        $force = $this->option('fetch');

        $this->line("Calling make:solution");
        $this->call('make:solution', [
            'name' => sprintf('_%d/_%02d', $year, $day)
        ]);

        $headers = [
            'User-Agent' => sprintf('%s/%s', 'github.com/mld/aoc', App::version()),
        ];
        $cookies = [
            'session' => env('AOC_SESSION_COOKIE', ''),
        ];
        $cookieDomain = '.adventofcode.com';

        // fetch input unless it already exists
        $input = Solution::inputFilename($year, $day);
        if (!Storage::exists($input)) {
            $this->line("Fetching input");
            $url = sprintf('https://adventofcode.com/%d/day/%d/input', $year, $day);
            $response = Http::withHeaders($headers)->withCookies($cookies, $cookieDomain)->get($url);
            Storage::put($input, $response->body());
        }

        // fetch puzzle
        $puzzle = Solution::inputFilename($year, $day, 'puzzle.html');
        if ($force || !Storage::exists($puzzle)) {
            $this->line("Fetching puzzle");
            $url = sprintf('https://adventofcode.com/%d/day/%d', $year, $day);
            $response = Http::withHeaders($headers)->withCookies($cookies, $cookieDomain)->get($url);

            Storage::put($puzzle, $response->body());
        }

        // parse puzzle
        $crawler = new Crawler(Storage::get($puzzle));

        $html = '';

        foreach ([1, 2] as $part) {
            // Add article to html (if found)
            $article = $crawler->filter('body > main > article')->eq($part - 1);
            if (!$article->count()) {
                // if the article isn't found, bail out
                break;
            }
            $html .= $article->html();


            // Add answer to html (if found)
            $answer = $crawler->filter('body > main > article + p')->eq($part - 1);
            if (!$answer->count()) {
                // if there's no answer, just continue for now, and try to see if there's still another article (our parsing failed)
                continue;
            }
            $html .= $answer->html();

            // Create input answer-file, if plaintext answer is found
            $answerReal = $answer->filter('code');
            if (!$answerReal->count()) {
                continue;
            }
            $answerFile = Solution::inputFilename($year, $day, 'input.part' . $part . '.answer');
            if (!Storage::exists($answerFile)) {
                // Create answer-file for input
                Storage::put($answerFile, $answerReal->innerText());
            }
        }

        $pandoc = new Pandoc();
        $markdown = $pandoc->runWith($html, ['from' => 'html', 'to' => 'gfm']);
        Storage::put(Solution::inputFilename($year, $day, 'puzzle.md'), $markdown);
        return 0;
    }
}
