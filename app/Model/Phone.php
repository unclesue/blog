<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $fillable = ['mobile'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
