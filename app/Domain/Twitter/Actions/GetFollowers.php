<?php

namespace App\Domain\Twitter\Actions;

use App\Domain\Twitter\Request;
use App\Domain\Twitter\CursoredResponse;

class GetFollowers
{
    /**
     * Pass null to screenName to get own followers.
     */
    public function __invoke($screenName = null)
    {
        $request = new Request(
            'get',
            'followers/ids',
            [
                'screen_name' => $screenName,
                'count' => 5000,
            ]
        );

        return new CursoredResponse($request);
    }
}
