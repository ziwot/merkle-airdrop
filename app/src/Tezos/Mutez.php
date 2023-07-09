<?php

namespace App\Tezos;

readonly class Mutez
{
    public function __construct(private int $mutez)
    {
    }

    public function tez(): float
    {
        return $this->mutez / 1000_000;
    }

    public function mutez(): int
    {
        return $this->mutez;
    }
}
