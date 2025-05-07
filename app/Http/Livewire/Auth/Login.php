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
    protected $listeners = ['setEmail', 'setPassword'];
    public function mount()
    {
        if (auth()->check()) {
            $user = auth()->user();

            return match ($user->role_id) {
                1 => redirect()->route('dashboard'),
                2 => redirect()->route('teacherdash.dashboard'),
                3 => redirect()->route('studentdash.dashboard'),
                4 => redirect()->route('parentdash.dashboard'),
                default => abort(403),
            };
        }
    }


    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = Auth::user(); // already logged in

            // Role-based redirect
            return match ($user->role_id) {
                1 => redirect()->route('dashboard'),                // Admin
                2 => redirect()->route('teacherdash.dashboard'),    // Teacher
                3 => redirect()->route('studentdash.dashboard'),    // Student
                4 => redirect()->route('parentdash.dashboard'),     // Parent (if added)
                default => abort(403, 'Unknown role'),
            };
        } else {
            $this->dispatch('login-failed');
        }
    }


    public function render()
    {
        return view('livewire.auth.login');
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
