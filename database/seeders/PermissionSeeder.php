<?php

namespace Database\Seeders;

use App\Enums\Permission as EnumsPermission;
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
        $now = now();
        Permission::insertOrIgnore(
            collect(EnumsPermission::cases())
                ->map(fn(EnumsPermission $permission) => [
                    'name' => $permission->value,
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->toArray()
        );
    }
}
