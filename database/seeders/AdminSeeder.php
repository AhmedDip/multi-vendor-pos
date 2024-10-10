<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        $admins = [
            'name'     => 'Admin One',
            'email'    => 'admin@one.com',
            'password' => Hash::make(User::DEFAULT_PASSWORD),
        ];
        $user   = User::query()->where('email', $admins['email'])->exists();
        if (!$user) {
           $admin = User::query()->create($admins);
           $admin->assignRole('Super Admin');
        }
    }
}
