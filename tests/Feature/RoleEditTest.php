<?php

use App\Enums\Permission;
use App\Enums\Role as EnumsRole;
use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\AttachAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->role = givePermission($this->user, [
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
    livewire(RoleResource\Pages\EditRole::class, [
        'record' => $this->role->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $this->role->name,
            'description' => $this->role->description,
        ]);
});

it('can save', function () {
    $data = Role::factory()->make();

    livewire(RoleResource\Pages\EditRole::class, ['record' => $this->role->getRouteKey()])
        ->fillForm([
            'name' => $data->name,
            'description' => $data->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Role::class, [
        'id' => $this->role->id,
        'name' => $data->name,
    ]);
});

it('can validate input', function () {
    livewire(RoleResource\Pages\EditRole::class, ['record' => $this->role->getRouteKey()])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

it('cannot edit with same name', function () {
    $newrole = Role::factory()->create();

    livewire(RoleResource\Pages\EditRole::class, ['record' => $newrole->getRouteKey()])
        ->fillForm([
            'name' => $this->role->name,
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

it('can list permissions', function () {
    livewire(RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $this->role,
        'pageClass' => RoleResource\Pages\EditRole::class,
    ])
        ->assertCanSeeTableRecords($this->role->permissions);
});

it('able to attach permission', function () {
    livewire(RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $this->role,
        'pageClass' => RoleResource\Pages\EditRole::class,
    ])
        ->assertTableHeaderActionsExistInOrder([AttachAction::class]);
});

it('can list users', function () {
    livewire(RoleResource\RelationManagers\UsersRelationManager::class, [
        'ownerRecord' => $this->role,
        'pageClass' => RoleResource\Pages\EditRole::class,
    ])
        ->assertCanSeeTableRecords($this->role->users);
});
