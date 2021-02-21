<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = [];

    public static function fetch($name = null, $default = null)
    {
        if (is_null($name)) {
            return self::get()->pluck('value', 'name');
        }

        return self::where('name', $name)
            ->first()->value ?? $default;
    }
}
