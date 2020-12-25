<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'semester',
        'code',
        'name',
        'frequency',
        'numbers',
        'disable',
        'open',
    ];
}
