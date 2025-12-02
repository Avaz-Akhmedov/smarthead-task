<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    public function run(): void
    {
        Role::query()->firstOrCreate([
            'name' => UserRoleEnum::ADMIN->value
        ], [
            'guard_name' => 'web'
        ]);

        Role::query()->firstOrCreate([
            'name' => UserRoleEnum::MANAGER->value
        ], [
            'guard_name' => 'web'
        ]);
    }
}
