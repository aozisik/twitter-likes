<?php

namespace App\Domain\Twitter\Actions;

use App\Domain\Twitter\Request;

class LikeTweet
{
    public function __invoke($tweetId)
    {
        $request = new Request(
            'post',
            'favorites/create',
            [
                'id' => $tweetId,
            ]
        );

        $request->make();
    }
}
