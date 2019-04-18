<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Twitter\Actions\LikeTweet;

class Follower extends Model
{
    protected $dates = [
        'engaged_at',
        'converted_at',
        'back_off_until',
    ];

    public function scopeEngageable($query)
    {
        return $query
            ->whereNull('engaged_at')
            ->where('interested', true)
            ->notInBackOff()
            ->orderBy('updated_at', 'asc');
    }

    public function scopeNotInBackOff($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('back_off_until')
                ->orWhere('back_off_until', '<=', Carbon::now());
        });
    }

    public function markAsNotInterested($reason)
    {
        $this->interested = false;
        $this->not_interested_reason = $reason;
        $this->save();
    }

    public function backOff($days)
    {
        $now = Carbon::now();
        $this->back_off_until = $now->addDays($days);
        $this->save();
    }

    public function engage($tweetId)
    {
        $like = resolve(LikeTweet::class)($tweetId);
        $this->engaged_at = Carbon::now();
        $this->save();
    }
}
