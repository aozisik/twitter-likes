<?php

namespace App\Domain\Twitter;

use App\Config;
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterClient
{
    public $connection;

    public function __construct()
    {
        $config = Config::fetch();

        $this->connection = new TwitterOAuth(
            $config->get('twitter_consumer_key'),
            $config->get('twitter_consumer_secret'),
            $config->get('twitter_access_token'),
            $config->get('twitter_access_token_secret')
        );
    }
}
