<?php
// filepath: /c:/laragon/www/VisionVoice - Copy/app/Models/Disability.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disability extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'application_disability');
    }
}