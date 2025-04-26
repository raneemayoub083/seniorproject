<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'audience',
    ];

    protected $casts = [
        'audience' => 'array', // because you are saving multiple audiences (json)
    ];
}