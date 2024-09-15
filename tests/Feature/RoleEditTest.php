<?php

use App\Enums\Permission;
use App\Enums\Role as EnumsRole;
use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\EditAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, [
        Permission::ROLES_VIEWANY,
        Permission::ROLES_UPDATE,
    ]);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(RoleResource::getUrl('edit', ['record' => Role::factory()->create()]))
        ->assertSuccessful();
});

it('can retrieve data', function () {
    $role = Role::factory()->create();

    livewire(RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $role->name,
            'description' => $role->description,
        ]);
});

it('can save', function () {
    $role = Role::factory()->create();
    $data = Role::factory()->make();

    livewire(RoleResource\Pages\EditRole::class, ['record' => $role->getRouteKey()])
        ->fillForm([
            'name' => $data->name,
            'description' => $data->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Role::class, [
        'id' => $role->id,
        'name' => $data->name,
    ]);
});

it('can validate input', function () {
    $role = Role::factory()->create();

    livewire(RoleResource\Pages\EditRole::class, ['record' => $role->getRouteKey()])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

it('cannot edit with same name', function () {
    $role = Role::factory()->create();
    $newrole = Role::factory()->create();

    livewire(RoleResource\Pages\EditRole::class, ['record' => $newrole->getRouteKey()])
        ->fillForm([
            'name' => $role->name,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'unique',
        ]);
});

it('cannot edit super admin', function () {
    $role = Role::factory()->create(['name' => EnumsRole::SUPER_ADMIN]);

    givePermission($this->user, Permission::ROLES_VIEW);

    livewire(RoleResource\Pages\ViewRole::class, ['record' => $role->getRouteKey()])
        ->assertActionHidden(EditAction::class);
});
