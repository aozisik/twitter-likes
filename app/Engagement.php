<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    protected $guarded = [];

    public function follower()
    {
        return $this->belongsTo(Follower::class);
    }
}
