<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RoleRedirectController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        return match ($user->role_id) {
            1 => redirect('/dashboard'),   
            2 => redirect('/teacher/dashboard'),
            3 => redirect('/student/dashboard'),
            4 => redirect('/parent/dashboard'),   
            default => abort(403, 'Unauthorized role'),
        };
    }
}
