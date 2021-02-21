<?php

namespace App\Console\Commands;

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
            DB::table('own_followers')->insert(
                collect($followers->ids)
                    ->map(function ($twitterId) {
                        return [
                            'twitter_id' => $twitterId,
                        ];
                    })
                    ->toArray()
            );
        }

        DB::table('followers')
            ->where('interested', 1)
            ->whereNotNull('engaged_at')
            ->whereNull('converted_at')
            ->join('own_followers', 'followers.twitter_id', '=', 'own_followers.twitter_id')
            ->update([
                'converted_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
