<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Batch insert permissions
        Permission::insertOrIgnore(
            collect(PermissionsEnum::cases())
                ->map(fn(PermissionsEnum $permission) => [
                    'name' => $permission->value,
                    'guard_name' => 'web'
                ])
                ->toArray()
        );
    }
}
