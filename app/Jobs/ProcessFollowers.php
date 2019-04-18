<?php

namespace App\Jobs;

use App\Target;
use App\Follower;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Twitter\Actions\LookupAccounts;

class ProcessFollowers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $target;

    public function __construct(Target $target)
    {
        $this->target = $target;
    }

    public function handle()
    {
        // 100 is the limit for Twitter's users/lookup endpoint
        $twitterLookupLimit = 100;

        $query = Follower::select('twitter_id')
            ->where('target', $this->target->screen_name)
            ->whereNull('interested');

        $count = $query->count();

        $followerIds = $query->take($twitterLookupLimit)
            ->pluck('twitter_id')
            ->toArray();

        $followers = resolve(LookupAccounts::class)($followerIds);

        DB::transaction(function () use ($followers) {
            foreach ($followers as $follower) {
                $this->processFollower($follower);
            }
        });

        if ($count > $twitterLookupLimit) {
            // Total followers in DB are greater than the lookup limit.
            // Let's do another run and process the rest
            dispatch(
                (new ProcessFollowers($this->target))->delay(5)
            );
        }
    }

    private function processFollower($follower)
    {
        $interest = $this->checkInterest($follower);

        DB::table('followers')
            ->where('target', $this->target->screen_name)
            ->where('twitter_id', $follower->id)
            ->update([
                'interested' => $interest->interested,
                'not_interested_reason' => $interest->reason,
                'screen_name' => $follower->screen_name,
                'avatar_url' => $follower->profile_image_url,
                'updated_at' => Carbon::now(),
            ]);
    }

    private function isOwnFollower($follower)
    {
        return DB::table('own_followers')
            ->where('twitter_id', $follower->id)
            ->exists();
    }

    private function checkInterest($follower)
    {
        $reason = null;

        if ($follower->protected) {
            $reason = 'Private account';
        } elseif ($follower->followers_count > 500) {
            $reason = 'Has more than 500 followers';
        } elseif ($this->isOwnFollower($follower)) {
            $reason = 'Is already a follower';
        }

        return (object) [
            'interested' => is_null($reason),
            'reason' => $reason,
        ];
    }
}
