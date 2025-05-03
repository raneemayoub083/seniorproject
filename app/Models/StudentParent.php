<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $fillable = ['user_id', 'phone_number'];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
