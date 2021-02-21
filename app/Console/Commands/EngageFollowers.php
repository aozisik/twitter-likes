<?php

namespace App\Console\Commands;

use App\Config;
use App\Follower;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Domain\Twitter\RateLimit;
use Illuminate\Support\Facades\DB;
use App\Domain\Twitter\RateLimiter;
use App\Domain\Twitter\Actions\GetLastTweet;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EngageFollowers extends Command
{
    protected $signature = 'followers:engage';
    protected $description = 'Engage with followers that we\'re interested about';

    /**
     * @var RateLimiter
     */
    private $likesLimiter;

    /**
     * @var RateLimiter
     */
    private $lastTweetLimiter;

    public function handle()
    {
        // Minutes
        $commandRunFrequency = 5;

        $this->likesLimiter = new RateLimiter(
            RateLimit::likes()->window($commandRunFrequency)
        );

        $this->lastTweetLimiter = new RateLimiter(
            RateLimit::userStatus()->window($commandRunFrequency)
        );

        while ($account = $this->nextAccount()) {
            if ($this->rateLimitsExhausted()) {
                // Stop now...
                $this->info('Stopping to prevent going over rate limits.');

                exit;
            }

            $this->engageFollower($account);
        }
    }

    private function rateLimitsExhausted()
    {
        return $this->likesLimiter->isExhausted()
            || $this->lastTweetLimiter->isExhausted();
    }

    private function engageFollower($follower)
    {
        $this->deDuplicateFollowers($follower);
        $this->info('Checking @' . $follower->screen_name);

        if ($this->isOwnFollower($follower)) {
            return $follower->markAsNotInterested('Already a follower');
        }

        try {
            $tweet = resolve(GetLastTweet::class)($follower->twitter_id);
            $this->lastTweetLimiter->hit();
        } catch (HttpException $e) {
            switch ($e->getStatusCode()) {
                case 404:
                    return $follower->markAsNotInterested('Account deleted/blocked.');

                case 401:
                    return $follower->markAsNotInterested('Account protected.');

                case 429:
                    $this->info('Stopping to prevent going over rate limits.');
                    exit;

                default:
                    // Don't handle other cases.
                    throw $e;
            }
        }

        if ($this->followerInactive($follower, $tweet)) {
            return $this->comment('@' . $follower->screen_name . '\'s account seems inactive.');
        }

        try {
            $follower->engage($tweet);
            $this->likesLimiter->hit();
        } catch (HttpException $e) {
            switch ($e->getStatusCode()) {
                case 429:
                    $this->info('Stopping to prevent going over rate limits.');
                    exit;

                default:
                    $follower->markAsNotInterested('Can\'t favorite tweet');
            }
        }
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
        if (! $tweet) {
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
