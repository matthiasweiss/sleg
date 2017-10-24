<?php

namespace App;

class Membership extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
