<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\Role;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, [
        Permission::USERS_VIEWANY,
        Permission::USERS_VIEW,
        Permission::ROLES_VIEWANY,
    ]);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(UserResource::getUrl('view', ['record' => User::factory()->create()]))
        ->assertSuccessful();
});

it('can retrieve data', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\ViewUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertSee($user->name)
        ->assertSee($user->email);
});

it('can list roles', function () {
    $role = Role::factory()->create();
    $user = User::factory()->create();
    $user->assignRole($role);

    livewire(UserResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $user,
        'pageClass' => UserResource\Pages\ViewUser::class,
    ])
        ->assertCanSeeTableRecords($user->roles);
});
