<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'semester',
        'code',
        'name',
        'track',
        'field',
        'frequency',
        'numbers',
        'disable',
        'open',
        'started_at',
        'stopped_at',
    ];
}
