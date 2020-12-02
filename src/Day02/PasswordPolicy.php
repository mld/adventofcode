<?php

namespace App\Day02;

class PasswordPolicy
{
    /** @var array<bool> */
    protected $rows;

    /**
     * ExpenseReport constructor.
     * @param array<string> $rows
     * @param bool $mode
     */
    public function __construct($rows = [], $mode = false)
    {
        $this->parseRows($rows, $mode);
    }

    /**
     * @param array<string> $rows
     * @param bool $mode
     */
    protected function parseRows($rows = [], $mode = false): void
    {
        foreach ($rows as $row) {
            $foundStrings = sscanf(trim($row), '%D-%D %c: %s', $min, $max, $char, $password);
            if ($foundStrings == -1) {
                continue;
            }
            $this->rows[trim($row)] = $this->checkValidity($min, $max, $char, $password, $mode);
        }
    }

    /**
     * @param int $min
     * @param int $max
     * @param string $char
     * @param string $password
     * @param bool $mode
     * @return bool
     */
    public function checkValidity(int $min, int $max, string $char, string $password, bool $mode): bool
    {
        if ($mode == false) {
            $frequency = count_chars($password, 0);
            if ($frequency[ord($char)] >= $min && $frequency[ord($char)] <= $max) {
                return true;
            }
        }

        if ($mode) {
            $count = 0;
            if ($password[$min - 1] == $char) {
                $count++;
            }
            if ($password[$max - 1] == $char) {
                $count++;
            }
            if ($count == 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return int
     */
    public function validPasswordCount(): int
    {
        $valid = 0;
        foreach ($this->rows as $row) {
            if ($row == true) {
                $valid++;
            }
        }
        return $valid;
    }

    /**
     * @return int
     */
    public function invalidPasswordCount(): int
    {
        $invalid = 0;
        foreach ($this->rows as $row) {
            if ($row == false) {
                $invalid++;
            }
        }
        return $invalid;
    }
}
