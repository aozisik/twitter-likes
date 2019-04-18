<?php

namespace App\Console\Commands;

use App\Config;
use App\Follower;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Domain\Twitter\Actions\GetLastTweet;

class EngageFollowers extends Command
{
    private $engagedCount = 0;

    protected $signature = 'followers:engage';
    protected $description = 'Engage with followers that we\'re interested about';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Twitter's rate limit for placing likes is 1,000 per day.
        // Therefore we'll play it nice and try not to exceed that.
        $dailyLimit = Config::fetch('max_likes_per_day', 1000);
        // this is set in Kernel.php
        $engageFrequencyMinutes = 5;
        $engageCountPerDay = (24 * 60) / $engageFrequencyMinutes;

        $perCall = floor($dailyLimit / $engageCountPerDay);
        $this->info('Will engage with ' . $perCall . ' users');

        while ($account = $this->nextAccount()) {
            if ($this->engagedCount === $perCall) {
                // Stop now...
                return;
            }
            if ($this->engageFollower($account)) {
                $this->engagedCount++;
            }
        }
    }

    private function engageFollower($follower)
    {
        $this->deDuplicateFollowers($follower);
        $this->info('Checking @' . $follower->screen_name);

        if ($this->isOwnFollower($follower)) {
            $follower->markAsNotInterested('Already a follower');
            return false;
        }

        $tweet = resolve(GetLastTweet::class)($follower->twitter_id);

        if ($this->followerInactive($follower, $tweet)) {
            $this->comment('@' . $follower->screen_name . '\'s account seems inactive.');
            return false;
        }

        $follower->engage($tweet->id);
        return true;
    }

    private function nextAccount()
    {
        return Follower::engageable()->first();
    }

    private function isOwnFollower($follower)
    {
        return DB::table('own_followers')
            ->where('twitter_id', $follower->twitter_id)
            ->exists();
    }

    private function isTweetFresh($tweet)
    {
        if (!$tweet) {
            // There's no tweet!
            return false;
        }
        $date = new Carbon($tweet->created_at);
        // Make sure tweet is new!
        return $date->diffInDays() <= Config::fetch('last_tweet_max_days_ago', 3);
    }

    private function followerInactive($follower, $lastTweet)
    {
        if ($this->isTweetFresh($lastTweet)) {
            return false;
        }

        $loseInterestIn = Config::fetch('notweet_days_to_lose_interest', 30);

        // Follower created 7 days ago and still has a stale tweet...
        if ($follower->created_at->diffInDays() >= $loseInterestIn) {
            $follower->markAsNotInterested('Account seems inactive.');
        } else {
            $follower->backOff(Config::fetch('recheck_tweets_days', 1));
        }

        return true;
    }

    private function handleFollower($follower)
    {
        $this->deDuplicateFollowers($follower);
    }

    private function deDuplicateFollowers($follower)
    {
        DB::table('followers')
            ->where('target', '!=', $follower->target)
            ->where('twitter_id', $follower->twitter_id)
            ->update([
                'interested' => false,
                'not_interested_reason' => 'Duplicate. Follower of @' . $follower->target,
                'updated_at' => Carbon::now(),
            ]);
    }
}
