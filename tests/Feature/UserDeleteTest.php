<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, [
        Permission::USERS_VIEWANY,
        Permission::USERS_VIEW,
        Permission::USERS_DELETE,
    ]);

    $this->actingAs($this->user);
});

it('able to delete', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertTableActionVisible(DeleteAction::class, record: $user);
});

it('can delete', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\ViewUser::class, ['record' => $user->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing(User::class, ['id' => $user->id]);
});

it('not able to delete self', function () {
    livewire(UserResource\Pages\ListUsers::class)
        ->assertTableActionHidden(DeleteAction::class, record: $this->user);
});

it('cannot delete self', function () {
    livewire(UserResource\Pages\ViewUser::class, ['record' => $this->user->getRouteKey()])
        ->assertActionHidden(DeleteAction::class);
});

it('not able to delete super admin', function () {
    $user = User::factory()->create();

    assignSuperAdminRole($user);

    livewire(UserResource\Pages\ListUsers::class)
        ->assertTableActionHidden(DeleteAction::class, record: $user);
});

it('cannot delete super admin', function () {
    $user = User::factory()->create();

    assignSuperAdminRole($user);

    livewire(UserResource\Pages\ViewUser::class, ['record' => $user->getRouteKey()])
        ->assertActionHidden(DeleteAction::class);
});
