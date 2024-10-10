<?php

namespace Database\Seeders;

use App\Models\RoleExtended;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Shop Owner',
            'Sales Executive',
        ];

        foreach ($roles as $role) {
            if (RoleExtended::query()->where('name', $role)->exists()) {
                continue;
            }
            RoleExtended::create([
                'name' => $role
            ]);
        }
    }
}
