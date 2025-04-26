<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{
    protected $fillable = ['student_id', 'label', 'landmarks'];
    protected $casts = ['landmarks' => 'array'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
