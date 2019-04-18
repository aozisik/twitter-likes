<?php

namespace App\Console\Commands;

use App\Follower;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Domain\Twitter\Actions\GetFollowers;

class TrackConversions extends Command
{
    protected $signature = 'followers:conversions';
    protected $description = 'Tracks conversions';

    public function handle()
    {
        $cursor = resolve(GetFollowers::class)();

        DB::table('own_followers')->delete();

        while ($followers = $cursor->next()) {
            Follower::where('interested', true)
                ->whereIn('twitter_id', $followers->ids)
                ->update([
                    'converted_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

            DB::table('own_followers')->insert(
                collect($followers->ids)
                    ->map(function ($twitterId) {
                        return [
                            'twitter_id' => $twitterId
                        ];
                    })
                    ->toArray()
            );
        }
    }
}
