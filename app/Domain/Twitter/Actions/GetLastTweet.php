<?php

namespace App\Domain\Twitter\Actions;

use App\Domain\Twitter\Request;

class GetLastTweet
{
    public function __invoke($twitterId)
    {
        $request = new Request(
            'get',
            'statuses/user_timeline',
            [
                'count' => 1,
                'user_id' => $twitterId,
                'include_rts' => false,
                'exclude_replies' => true,
            ]
        );

        $response = $request->make();

        if (!is_array($response) || !count($response)) {
            return null;
        }

        return $response[0];
    }
}
