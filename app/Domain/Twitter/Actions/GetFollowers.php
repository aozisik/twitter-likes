<?php

namespace App\Domain\Twitter\Actions;

use App\Domain\Twitter\Request;
use App\Domain\Twitter\CursoredResponse;

class GetFollowers
{
    public function __invoke($screenName)
    {
        $request = new Request(
            'get',
            'followers/list',
            ['screen_name' => $screenName]
        );

        return new CursoredResponse($request);
    }
}
