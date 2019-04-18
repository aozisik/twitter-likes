<?php

namespace App\Domain\Twitter\Actions;

use App\Domain\Twitter\Request;

class LookupAccounts
{
    public function __invoke(array $userIds)
    {
        $request = new Request(
            'post',
            'users/lookup',
            ['user_id' => implode(',', $userIds)]
        );

        return $request->make();
    }
}
