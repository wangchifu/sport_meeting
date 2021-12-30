<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolApi extends Model
{
    protected $fillable = [
        'code',
        'client_id',
        'client_secret',
    ];
}
