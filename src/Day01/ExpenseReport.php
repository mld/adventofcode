<?php

namespace App\Day01;

class ExpenseReport
{
    /** @var array<string> */
    protected $rows;

    /**
     * ExpenseReport constructor.
     * @param array<string> $rows
     */
    public function __construct($rows = [])
    {
        $this->rows = $rows;
    }

    /**
     * @param int $sum
     * @return array<int>
     */
    public function getRowsBySum(int $sum = 2020): array
    {
        foreach ($this->rows as $row) {
            foreach ($this->rows as $row2) {
                if ((int)$row + (int)$row2 == $sum) {
                    return [(int)$row, (int)$row2];
                }
            }
        }
        return [];
    }

    /**
     * @param int $sum
     * @return array<int>
     */
    public function getThreeRowsBySum(int $sum = 2020): array
    {
        foreach ($this->rows as $row) {
            foreach ($this->rows as $row2) {
                if ((int)$row + (int)$row2 > $sum) {
                    continue;
                }
                foreach ($this->rows as $row3) {
                    if ((int)$row + (int)$row2 + (int)$row3 == $sum) {
                        return [(int)$row, (int)$row2, (int)$row3];
                    }
                }
            }
        }
        return [];
    }
}
