<?php

namespace App\Http\Controllers;

use App\Follower;

class FollowersController extends Controller
{
    public function index()
    {
        $query = Follower::orderBy('updated_at', 'desc');

        if (request('filter') === 'engaged') {
            $query->whereNotNull('engaged_at');
        }

        if (request('filter') === 'converted') {
            $query->whereNotNull('converted_at');
        }

        $followers = $query->paginate(30);

        return view('pages.followers.index')->with(compact('followers'));
    }
}
