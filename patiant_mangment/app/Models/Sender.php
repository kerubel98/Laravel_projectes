<?php

namespace App\Models;

class Sender
{
    public function emails()
    {
        return $this->belongsToMany(Email::class);
    }
}
