<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code',
        'order',
        'name',
        'group',
        'type',
        'limit',
        'disable',
    ];
}
