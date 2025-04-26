<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
      
        User::create([
            'name' => 'admin',
            'email' => 'admin1@school.com',
            'password' => Hash::make('test123'),
            'role_id' => 1,  // Admin role
        ]);
    }
}