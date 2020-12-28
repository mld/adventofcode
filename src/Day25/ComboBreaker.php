<?php

namespace App\Day25;

class ComboBreaker
{
    public const DENOMINATOR = 20201227;
    public const INITIAL_SUBJECT_NUMBER = 7;
    /**
     * @var array<int>
     */
    protected array $input;

    /**
     * ComboBreaker constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        foreach ($input as $item) {
            if (strlen(trim($item)) > 0) {
                $this->input[] = (int)trim($item);
            }
        }
    }

    public function findLoopSize(int $publicKey, int $initialSubjectNumber): int
    {
        $value = 1;
        $loopSize = 0;
        do {
            $loopSize++;
            $value = ($value * $initialSubjectNumber) % self::DENOMINATOR;
        } while ($value != $publicKey);
        return $loopSize;
    }

    public function findEncryptionKey(int $subjectNumber, int $loopSize): int
    {
        $value = 1;
        while ($loopSize > 0) {
            $loopSize--;
            $value = ($value * $subjectNumber) % self::DENOMINATOR;
        }
        return $value;
    }

    public function part1(): int
    {
        $cardPubKey = $this->input[0];
        $doorPubKey = $this->input[1];
        $cardLoopSize = $this->findLoopSize($cardPubKey, self::INITIAL_SUBJECT_NUMBER);
        return $this->findEncryptionKey($doorPubKey, $cardLoopSize);
    }
}
