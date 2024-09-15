<?php

use App\Enums\Permission;
use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermissions($this->user, [
        Permission::ROLES_VIEWANY,
        Permission::ROLES_CREATE,
    ]);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(RoleResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create', function () {
    $data = Role::factory()->make();

    livewire(RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => $data->name,
            'description' => $data->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Role::class, [
        'name' => $data->name,
    ]);
});

it('can validate input', function () {
    livewire(RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

it('cannot create with same name', function () {
    $role = Role::factory()->create();
    $data = Role::factory()->make(['name' => $role->name]);

    livewire(RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => $data->name,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'unique',
        ]);
});
