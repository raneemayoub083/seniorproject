<?php
namespace App\Mail;

use App\Models\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeTeacherMail extends Mailable
{
use Queueable, SerializesModels;

public $teacher;
public $password;
public $email;

public function __construct(Teacher $teacher, $password, $email)
{
$this->teacher = $teacher;
$this->password = $password;
$this->email =$email;
}

public function build()
{
return $this->subject('Welcome to the School System')
->view('emails.welcome_teacher')
->with([
'name' => $this->teacher->first_name . ' ' . $this->teacher->last_name,
'email' => $this->email,
'password' => $this->password,
]);
}
}