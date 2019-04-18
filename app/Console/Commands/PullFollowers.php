<?php

namespace App\Console\Commands;

use App\Target;
use Illuminate\Console\Command;
use App\Jobs\PullFollowers as PullFollowersJob;

class PullFollowers extends Command
{
    private $delay = 0;
    protected $signature = 'followers:pull';
    protected $description = 'Pull target accounts followers';

    public function handle()
    {
        Target::get()->each(function ($target) {
            dispatch(
                (new PullFollowersJob($target))->delay(now()->addMinutes($this->delay))
            );
            // Add 10 minutes each time
            $this->delay += 10;
        });
    }
}
