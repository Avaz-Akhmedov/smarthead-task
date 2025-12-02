<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'admin',
            'password' => 'admin'
        ]);

        User::query()->firstOrCreate([
            'email' => 'manager@gmail.com'
        ], [
            'name' => 'manager',
            'password' => 'manager'
        ]);
    }
}
