<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required',
    ];

    public function mount()
    {
        if (auth()->check()) {
            $user = auth()->user();  // Get the currently authenticated user
            $role_id = $user->role_id;  // Get the role_id of the currently authenticated user

            // Check if the user is an admin (assuming role_id for admin is 1)
            if ($role_id == 1) {
                return redirect()->route('dashboard');  // Redirect to admin dashboard
            }

            // Check if the user is a teacher (assuming role_id for teacher is 2)
            if ($role_id == 2) {
                return redirect()->route('teacherdash.dashboard');  // Redirect to teacher dashboard
            }
            if($role_id == 3){
                return redirect()->route('studentdash.dashboard');
            }

         
        }

        $this->fill(['email' => 'admin2@school.com', 'password123' => 'secret']);
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = User::where('email', $this->email)->first();
            Auth::login($user, $this->remember_me);

            // Trigger SweetAlert for success
            $this->dispatch('login-success');
        } else {
            // Trigger SweetAlert for failure
            $this->dispatch('login-failed');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
