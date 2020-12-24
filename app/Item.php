<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code',
        'action_id',
        'order',
        'name',
        'group',
        'type',
        'years',
        'limit',
        'people',
        'disable',
    ];
}
