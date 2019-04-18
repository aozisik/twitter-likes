<?php

namespace App\Events;

use App\Target;
use App\Jobs\ProcessFollowers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class FollowersPulled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $target;

    public function __construct(Target $target)
    {
        $this->target = $target;
        dispatch(new ProcessFollowers($target));
    }
}
