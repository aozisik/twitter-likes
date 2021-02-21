<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Twitter\Actions\LikeTweet;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Follower extends Model
{
    protected $dates = [
        'engaged_at',
        'converted_at',
        'back_off_until',
    ];

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }

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

    public function engage($tweet)
    {
        try {
            resolve(LikeTweet::class)($tweet->id);
        } catch (HttpException $e) {
            // If tweet is not already liked.
            if ($e->getStatusCode() !== 403) {
                throw $e;
            }
        }

        $this->engaged_at = Carbon::now();
        $this->save();

        $this->engagements()->create([
            'follower_id' => $this->id,
            'tweet_id' => $tweet->id,
            'tweet_url' => 'https://twitter.com/' . $this->screen_name . '/status/' . $tweet->id,
        ]);
    }

    public function getAvatarUrlAttribute()
    {
        return str_replace('http://', 'https://', $this->attributes['avatar_url']);
    }
}
