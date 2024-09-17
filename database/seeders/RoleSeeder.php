<?php

namespace Database\Seeders;

use App\Enums\Role as EnumsRole;
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
        Role::updateOrCreate(
            ['name' => EnumsRole::SUPER_ADMIN->value],
            ['description' => 'Has full access to all system features and settings.']
        );
    }
}
