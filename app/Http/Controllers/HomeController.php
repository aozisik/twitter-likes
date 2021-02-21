<?php

namespace App\Http\Controllers;

use App\Target;
use App\Follower;
use App\Engagement;

class HomeController extends Controller
{
    public function index()
    {
        $targets = Target::count();
        $followers = Follower::where('interested', true)->count();

        $engaged = Engagement::count();

        $conversions = Follower::whereNotNull('converted_at')
            ->count();

        return view('home')->with(compact(
            'targets',
            'followers',
            'engaged',
            'conversions'
        ));
    }
}
