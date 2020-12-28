<?php

namespace App\Day06;

class CustomsDeclarationForms
{
    /** @var array<mixed> */
    protected $list;

    /**
     * Passports constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->list = [];
        $raw = join('', $rows);
        $parts = explode("\n\n", $raw);
        printf("Found %d groups\n", count($parts));
        $this->list = $parts;
    }

    public function countDistinctYesInGroup(string $input): int
    {
        $entry = preg_replace('/\s+/', '', $input);
        if ($entry === null) {
            return 0;
        }
        if (strlen($entry) == 0) {
            return 0;
        }

        $count = count_chars($entry, 1);
        if (!is_array($count)) {
            return 0;
        }
        return count($count);
    }

    public function countUnanimousYesInGroup(string $input): int
    {
        $input = explode("\n", $input);
        $work = null;
        foreach ($input as $entry) {
            $entry = preg_replace('/\s+/', '', $entry);
            if ($entry === null) {
                continue;
            }
            if (strlen($entry) == 0) {
                continue;
            }

            $entryCount = count_chars($entry, 1);
            if (!is_array($entryCount)) {
                continue;
            }
            if ($work === null) {
                $work = $entryCount;
            }
            $work = array_intersect_key($work, $entryCount);
        }
        return count($work);
    }

    public function sumDistinctYesPerGroup(): int
    {
        $sum = 0;
        foreach ($this->list as $entry) {
            $sum += $this->countDistinctYesInGroup($entry);
        }
        return $sum;
    }

    public function sumUnanimousYesPerGroup(): int
    {
        $sum = 0;
        foreach ($this->list as $entry) {
            $sum += $this->countUnanimousYesInGroup($entry);
        }
        return $sum;
    }
}
