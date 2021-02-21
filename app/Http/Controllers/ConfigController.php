<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Support\Facades\DB;
use App\Domain\Twitter\Actions\VerifyAccess;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::fetch();

        return view('pages.config.index')->with(compact('configs'));
    }

    private function storeFields(array $fields)
    {
        foreach ($fields as $field) {
            $config = Config::firstOrNew(['name' => $field]);
            $config->value = request($field);
            $config->save();
        }

        return back()->withSuccess('Config has been updated.');
    }

    public function store()
    {
        if (request('tuning')) {
            return $this->storeTuning();
        }

        $fields = [
            'twitter_consumer_key',
            'twitter_consumer_secret',
            'twitter_access_token',
            'twitter_access_token_secret',
        ];

        if (! resolve(VerifyAccess::class)(request()->only($fields))) {
            return back()->withError('Invalid Twitter credentials. / Twitter API Error');
        }

        DB::table('own_followers')->delete();

        return $this->storeFields($fields);
    }

    private function storeTuning()
    {
        $fields = [
            'max_likes_per_day',
            'max_followers',
            'last_tweet_max_days_ago',
            'notweet_days_to_lose_interest',
            'recheck_tweets_days',
        ];

        return $this->storeFields($fields);
    }
}
