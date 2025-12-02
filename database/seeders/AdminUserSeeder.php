<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;


class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
            'name' => 'Admin',
            'password' => Hash::make('123456'),
            ]
        );
        $adminRole = Role::where('name', 'Administrador')->first();
        $user->assignRole($adminRole);
    }
}
