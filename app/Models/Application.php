<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'profile_img',
        'phone',
        'address',
        'grade_id',
        'parents_names',
        'parents_contact_numbers',
        'id_card_img',
        'precertificate',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function disabilities()
    {
        return $this->belongsToMany(Disability::class, 'application_disability');
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

}