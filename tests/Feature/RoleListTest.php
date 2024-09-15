<?php

use App\Enums\Permission;
use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, Permission::ROLES_VIEWANY);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(RoleResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list users', function () {
    $roles = Role::factory(2)->create();

    livewire(RoleResource\Pages\ListRoles::class)
        ->assertCanSeeTableRecords($roles);
});
