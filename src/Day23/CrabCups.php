<?php

namespace App\Day23;

class CrabCups
{
    public const PART1_ROUNDS = 100;
    public const PART2_CUPS = 1_000_000;
    public const PART2_ROUNDS = 10_000_000;
    /**
     * @var array<int>
     */
    protected array $input;

    /**
     * CrabCups constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        $this->input = array_map('intval', str_split(trim($input[0])));
    }

    public function part1(): int
    {
        $cupsById = [];
        foreach ($this->input as $value) {
            $cupsById[$value] = new Cup($value);
            if (isset($previousCup)) {
                $cupsById[$previousCup]->setNext($cupsById[$value]);
            }
            $previousCup = $value;
        }

        $first = $cupsById[array_key_first($cupsById)];
        $cupsById[array_key_last($cupsById)]->setNext($first);
        $current = $cupsById[array_key_first($cupsById)];

        $step = 0;
        while (++$step <= self::PART1_ROUNDS) {
            $this->moveCups($cupsById, $current);
            $current = $current->getNext();
        }

        $valueString = '';
        $current = $cupsById[1];
        do {
            $current = $current->getNext();
            $valueString .= $current->getId();
        } while ($current->getNext()->getId() !== 1);

        return (int)$valueString;
    }

    public function part2(): int
    {
        $cupsById = [];
        foreach (array_merge($this->input, range(max($this->input) + 1, self::PART2_CUPS)) as $value) {
            $cupsById[$value] = new Cup($value);
            if (isset($previousCup)) {
                $cupsById[$previousCup]->setNext($cupsById[$value]);
            }
            $previousCup = $value;
        }

        $first = $cupsById[array_key_first($cupsById)];
        $cupsById[array_key_last($cupsById)]->setNext($first);

        $step = 0;
        $current = $cupsById[array_key_first($cupsById)];
        while (++$step <= self::PART2_ROUNDS) {
            $this->moveCups($cupsById, $current);
            $current = $current->getNext();
        }

        return $cupsById[1]->getNext()->getId() * $cupsById[1]->getNext()->getNext()->getId();
    }

    /**
     * @param array<int,Cup> $cupsById
     * @param Cup $current
     */
    protected function moveCups(array $cupsById, Cup $current): void
    {
        $pick = [];
        $pickedId = [];
        for ($n = 0; $n < 3; $n++) {
            $pick[$n] = $current->getNext();
            $pick[$n]->remove();
            $pickedId[] = $pick[$n]->getId();
        }

        $insertAfterId = $current->getId() - 1;
        while ($insertAfterId < 1 || in_array($insertAfterId, $pickedId)) {
            if ($insertAfterId-- < 1) {
                $insertAfterId = count($cupsById);
            }
        }

        $cupToInsertAfter = $cupsById[$insertAfterId];
        $cupToInsertAfter->insertAfter(array_pop($pick));
        $cupToInsertAfter->insertAfter(array_pop($pick));
        $cupToInsertAfter->insertAfter(array_pop($pick));
    }
}
