<?php

namespace App\Helpers;

class Position
{
    public int $x;
    public int $y;
    protected bool $allowNegative;
    public function __construct(int $x = 0, int $y = 0, bool $allowNegative = true)
    {

        $this->allowNegative = $allowNegative;

        if(!$this->allowNegative) {
            $this->validateNonNegativeInteger($x);
            $this->validateNonNegativeInteger($y);
        }

        $this->x = $x;
        $this->y = $y;
    }

    public function equals(Position $p2): bool
    {
        return ($this->x == $p2->x && $this->y == $p2->y);
    }

    public function getRow(): int
    {
        return $this->x;
    }

    public function getColumn(): int
    {
        return $this->y;
    }

    public function isEqualTo(Position $other): bool
    {
        return $this->equals($other);
    }

    public function isAdjacentTo(Position $other): bool
    {
        return abs($this->getRow() - $other->getRow()) <= 1 && abs($this->getColumn() - $other->getColumn()) <= 1;
    }

    public function getUniqueNodeId(): string
    {
        return $this->x . 'x' . $this->y;
    }

    private function validateNonNegativeInteger(int $integer): void
    {
        if ($integer < 0) {
            throw new \InvalidArgumentException("Invalid non negative integer: $integer");
        }
    }
}
