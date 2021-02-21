<?php

namespace App\Domain\Twitter;

class RateLimiter
{
    private $counter = 0;

    private $limit;

    public function __construct(RateLimit $rateLimit)
    {
        $this->limit = $rateLimit->limit;
    }

    public function hit()
    {
        $this->counter++;
    }

    public function isExhausted()
    {
        return $this->counter >= $this->limit;
    }
}
