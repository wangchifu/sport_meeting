<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'code',
        'name',
        'frequency',
        'numbers',
        'disable',
    ];
}
