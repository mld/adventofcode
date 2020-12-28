<?php

namespace App\Day23;

class Cup
{
    private int $id;
    private Cup $next;
    private Cup $previous;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function setPrevious(Cup $previous): void
    {
        $this->previous = $previous;
    }

    public function setNext(Cup $next): void
    {
        $this->next = $next;
        $next->setPrevious($this);
    }

    public function remove(): void
    {
        $this->previous->setNext($this->next);
    }

    public function insertAfter(Cup $next): void
    {
        $next->setNext($this->next);
        $next->setPrevious($this);
        $this->next = $next;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNext(): Cup
    {
        return $this->next;
    }

    public function getPrevious(): Cup
    {
        return $this->previous;
    }
}
