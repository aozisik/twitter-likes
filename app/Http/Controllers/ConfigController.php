<?php

namespace App\Http\Controllers;

use App\Config;
use App\Domain\Twitter\Actions\VerifyAccess;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::fetch();
        return view('pages.config.index')->with(compact('configs'));
    }

    public function store()
    {
        $fields = [
            'twitter_consumer_key',
            'twitter_consumer_secret',
            'twitter_access_token',
            'twitter_access_token_secret',
        ];

        if (!resolve(VerifyAccess::class)(request()->only($fields))) {
            return back()->withError('Invalid Twitter credentials. / Twitter API Error');
        }

        foreach ($fields as $field) {
            $config = Config::firstOrNew(['name' => $field]);
            $config->value = request($field);
            $config->save();
        }

        return back()->withSuccess('Config has been updated.');
    }
}
