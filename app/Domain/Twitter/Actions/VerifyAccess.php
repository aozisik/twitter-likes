<?php

namespace App\Domain\Twitter\Actions;

use Exception;
use Abraham\TwitterOAuth\TwitterOAuth;

class VerifyAccess
{
    public function __invoke(array $credentials)
    {
        try {
            $this->verifyCredentials($credentials);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function verifyCredentials(array $credentials)
    {
        $connection = new TwitterOAuth(
            $credentials['twitter_consumer_key'],
            $credentials['twitter_consumer_secret'],
            $credentials['twitter_access_token'],
            $credentials['twitter_access_token_secret']
        );

        $content = $connection->get('account/verify_credentials');
    }
}
