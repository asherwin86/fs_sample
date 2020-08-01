<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'blurb',
        'status',
    ];

    const STATUSES = [
        'not started',
        'started',
        'finished',
        'retired',
    ];
}
