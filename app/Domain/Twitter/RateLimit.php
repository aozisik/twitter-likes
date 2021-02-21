<?php

namespace App\Domain\Twitter;

use App\Config;

class RateLimit
{
    public $minutes;

    public $limit;

    public function __construct($minutes, $limit)
    {
        $this->minutes = (int) $minutes;
        $this->limit = (int) $limit;
    }

    /**
     * Extrapolates rate limit to a different window.
     */
    public function window($windowMinutes)
    {
        return new self(
            $rate = $this->minutes / $windowMinutes,
            $this->limit / $rate
        );
    }

    public static function userStatus()
    {
        // Don't go over 900 requests in 15 minutes
        return new self(15, 900);
    }

    public static function likes()
    {
        return new self(24 * 60, Config::fetch('max_likes_per_day', 1000));
    }
}
