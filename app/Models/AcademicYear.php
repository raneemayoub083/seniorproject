<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'academic_years';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'application_opening',
        'application_expiry',
        'status',
    ];
    public function sections()
    {
        return $this->hasMany(Section::class); // or belongsToMany if it's a many-to-many relation
    }
}