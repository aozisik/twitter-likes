<?php

namespace App\Http\Controllers;

use App\Follower;

class FollowersController extends Controller
{
    public function index()
    {
        $followers = Follower::orderBy('updated_at', 'desc')->paginate(30);

        return view('pages.followers.index')->with(compact('followers'));
    }
}
