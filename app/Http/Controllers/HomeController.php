<?php

namespace App\Http\Controllers;

use App\Target;
use App\Follower;

class HomeController extends Controller
{
    public function index()
    {
        $targets = Target::count();
        $followers = Follower::where('interested', true)->count();

        $engages = Follower::whereNotNull('engaged_at')
            ->count();

        $conversions = Follower::whereNotNull('converted_at')
            ->count();

        return view('home')->with(compact(
            'targets',
            'followers',
            'engages',
            'conversions'
        ));
    }
}
