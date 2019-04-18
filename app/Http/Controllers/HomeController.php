<?php

namespace App\Http\Controllers;

use App\Domain\Twitter\Actions\GetFollowers;

class HomeController extends Controller
{
    public function index()
    {
        $followersCursor = resolve(GetFollowers::class)('_aozisik');

        $users = [];
        while ($followers = $followersCursor->next()) {
            $users = array_merge($users, $followers->users);
        }

        return view('home');
    }
}
