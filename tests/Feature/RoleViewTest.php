<?php

use App\Enums\Permission;
use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, [
        Permission::ROLES_VIEWANY,
        Permission::ROLES_VIEW,
        Permission::USERS_VIEWANY,
    ]);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(RoleResource::getUrl('view', ['record' => Role::factory()->create()]))
        ->assertSuccessful();
});

it('can retrieve data', function () {
    $role = Role::factory()->create();

    livewire(RoleResource\Pages\ViewRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->assertSee($role->name)
        ->assertSee($role->description);
});

it('can list permissions', function () {
    $role = Role::factory()->create();
    $role->givePermissionTo(Permission::ROLES_VIEWANY);

    livewire(RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
        'pageClass' => RoleResource\Pages\ViewRole::class,
    ])
        ->assertCanSeeTableRecords($role->permissions);
});

it('can list users', function () {
    $role = Role::factory()->create();
    $this->user->assignRole($role);

    livewire(RoleResource\RelationManagers\UsersRelationManager::class, [
        'ownerRecord' => $role,
        'pageClass' => RoleResource\Pages\ViewRole::class,
    ])
        ->assertCanSeeTableRecords($role->users);
});
