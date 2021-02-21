<?php

namespace App\Jobs;

use App\Target;
use App\Follower;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Events\FollowersPulled;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Domain\Twitter\Actions\GetAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Twitter\Actions\GetFollowers;

class PullFollowers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $target;

    public function __construct(Target $target)
    {
        $this->target = $target;
    }

    public function handle()
    {
        if ($this->followersNotChanged()) {
            // Nothing to do!
            return;
        }

        $cursor = resolve(GetFollowers::class)($this->target->screen_name);

        while ($followers = $cursor->next()) {
            //
            $excluded = $this->pulledFollowers($followers->ids);
            $idsToInsert = array_diff($followers->ids, $excluded);
            $this->massInsertFollowers($idsToInsert);

            if (count($idsToInsert) !== count($followers->ids)) {
                // At least one entry in this list co-incides with ours.
                // No need to pull more as the order of the Twitter API gives the most recent follower first
                // And the next cursor likely contains already pulled followers
                break;
            }
        }

        event(new FollowersPulled($this->target));
    }

    private function massInsertFollowers(array $followerIds)
    {
        DB::table('followers')->insert(
            collect($followerIds)->map(function ($id) {
                return [
                    'target' => $this->target->screen_name,
                    'twitter_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            })->toArray()
        );
    }

    private function pulledFollowers(array $followerIds)
    {
        return Follower::select('twitter_id')
            ->where('target', $this->target->screen_name)
            ->whereIn('twitter_id', $followerIds)
            ->pluck('twitter_id')
            ->toArray();
    }

    private function followersNotChanged()
    {
        if ($this->notPulledBefore()) {
            // Force a pull. We haven't pulled this account before.
            return false;
        }

        // Compare followers count
        $account = resolve(GetAccount::class)($this->target->screen_name);

        return $account->followers_count === $this->target->followers_count;
    }

    private function notPulledBefore()
    {
        return ! Follower::where('target', $this->target->screen_name)->exists();
    }
}
