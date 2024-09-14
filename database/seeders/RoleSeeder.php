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
        $now = now();

        Role::insertOrIgnore(
            collect(RolesEnum::cases())
                ->map(fn(RolesEnum $role) => [
                    'name' => $role->value,
                    'description' => 'Has full access to all system features and settings.',
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->toArray()
        );
    }
}
