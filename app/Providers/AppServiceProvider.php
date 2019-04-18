<?php

namespace App\Providers;

use App\Config;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->singleton('twitterClient', function () {
            $config = Config::fetch();
            return new TwitterOAuth(
                $config->get('twitter_consumer_key'),
                $config->get('twitter_consumer_secret'),
                $config->get('twitter_access_token'),
                $config->get('twitter_access_token_secret')
            );
        });
    }
}
