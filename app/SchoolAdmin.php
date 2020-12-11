<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolAdmin extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
