<?php

namespace App\Http\Controllers;

use Exception;
use App\Target;
use App\Follower;
use App\Jobs\PullFollowers;
use Illuminate\Http\Request;
use App\Domain\Twitter\Actions\GetAccount;

class TargetsController extends Controller
{
    public function index()
    {
        $targets = Target::get();
        return view('pages.targets.index')->with(compact('targets'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'screen_name' => 'required|unique:targets'
        ]);

        try {
            $account = resolve(GetAccount::class)($request->get('screen_name'));
        } catch (Exception $e) {
            return back()->withError('Account not found!');
        }

        $data = $request->only(['screen_name']) + [
            'avatar_url' => $account->profile_image_url,
            'followers_count' => $account->followers_count,
        ];

        $target = Target::create($data);
        dispatch(new PullFollowers($target));

        return back()->withSuccess('Target added!');
    }

    public function destroy(Target $target)
    {
        $target->delete();
        Follower::where('target', $target->screen_name)->delete();

        return back()->withSuccess('Target deleted.');
    }
}
