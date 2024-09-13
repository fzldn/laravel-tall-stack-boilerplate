<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insertOrIgnore(
            collect(RolesEnum::cases())
                ->map(fn(RolesEnum $role) => [
                    'name' => $role->value,
                    'guard_name' => 'web'
                ])
                ->toArray()
        );
    }
}
