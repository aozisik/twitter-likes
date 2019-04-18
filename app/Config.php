<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = [];

    public static function fetch()
    {
        return self::get()->pluck('value', 'name');
    }
}
