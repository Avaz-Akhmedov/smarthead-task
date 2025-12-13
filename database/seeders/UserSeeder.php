<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'admin',
            'password' => 'admin'
        ]);
        $admin->assignRole(UserRoleEnum::ADMIN->value);

        $manager = User::query()->firstOrCreate([
            'email' => 'manager@gmail.com'
        ], [
            'name' => 'manager',
            'password' => 'manager'
        ]);

        $manager->assignRole(UserRoleEnum::MANAGER->value);

        User::query()->firstOrCreate(['email' => 'user@gmail.com'], [
            'name' => 'user',
            'password' => 'user'
        ]);
    }
}
