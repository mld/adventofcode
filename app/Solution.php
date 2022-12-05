<?php

namespace App;


use Illuminate\Support\Facades\Storage;

abstract class Solution
{
    public const __DEFAULT_INPUT = 'input';

    public string $year;
    public string $day;
    public string $raw;
    public string $input;

    protected bool $verbose;

    public function __construct(array $attributes = [])
    {
        $this->year = $attributes['year'];
        $this->day = $attributes['day'];

        $inputFilename = $attributes['file'] ?? self::__DEFAULT_INPUT;
        $this->raw = Storage::get(self::inputFilename($this->year, $this->day, $inputFilename));

        $this->verbose = $attributes['verbose'] ?? false;
    }

    protected function debug(string $string): void
    {
        if (!$this->verbose) {
            return;
        }
        printf("%s", $string);
    }

    public function getLinesFromRaw(bool $trim = true): array
    {
        if ($trim) {
            return explode("\n", trim($this->raw));
        }
        return explode("\n", $this->raw);
    }

    public function getArrayFromRaw(): array
    {
        $map = [];
        foreach ($this->getLinesFromRaw() as $item) {
            if (strlen(trim($item)) > 0) {
                $map[] = str_split(trim($item));
            }
        }
        return $map;
    }

    public static function inputFilename($year, $day, $file = self::__DEFAULT_INPUT): string
    {
        return sprintf('%d/%02d/%s', $year, $day, $file);
    }

    public function solve(int $part): string
    {
        return match ($part) {
            1 => (string)$this->part1(),
            2 => (string)$this->part2(),
            default => 'no such part',
        };
    }

    abstract public function part1();

    abstract public function part2();
}
