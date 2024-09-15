<?php

use App\Enums\Permission;
use App\Enums\Role as EnumsRole;
use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, [
        Permission::ROLES_VIEWANY,
        Permission::ROLES_VIEW,
        Permission::ROLES_DELETE,
    ]);

    $this->actingAs($this->user);
});

it('can delete', function () {
    $role = Role::factory()->create();

    livewire(RoleResource\Pages\ViewRole::class, ['record' => $role->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($role);
});

it('not able to delete super admin', function () {
    $role = Role::factory()->create(['name' => EnumsRole::SUPER_ADMIN]);

    livewire(RoleResource\Pages\ViewRole::class, ['record' => $role->getRouteKey()])
        ->assertActionHidden(DeleteAction::class);

    livewire(RoleResource\Pages\ListRoles::class)
        ->assertTableActionDoesNotExist(DeleteAction::class, record: $role);
});
