<?php

namespace App\Http\Controllers;

use App\Config;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::get()->keyBy('name');
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

        foreach ($fields as $field) {
            $config = Config::firstOrNew(['name' => $field]);
            $config->value = request($field);
            $config->save();
        }

        return back()->withSuccess('Config has been updated.');
    }
}
