<?php

namespace App\Domain\Twitter\Actions;

use App\Domain\Twitter\Request;

class GetAccount
{
    public function __invoke($screenName)
    {
        $request = new Request(
            'get',
            'users/show',
            ['screen_name' => $screenName]
        );

        return $request->make();
    }
}
