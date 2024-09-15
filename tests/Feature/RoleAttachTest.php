<?php

use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Filament\Tables\Actions\AttachAction;

use function Pest\Livewire\livewire;

it('able to attach user', function () {
    $role = Role::factory()->create();

    livewire(RoleResource\RelationManagers\UsersRelationManager::class, [
        'ownerRecord' => $role,
        'pageClass' => RoleResource\Pages\EditRole::class,
    ])
        ->assertTableHeaderActionsExistInOrder([AttachAction::class])
        ->assertSuccessful();
});

it('able to attach role', function () {
    $user = User::factory()->create();

    livewire(UserResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $user,
        'pageClass' => UserResource\Pages\EditUser::class,
    ])
        ->assertTableHeaderActionsExistInOrder([AttachAction::class])
        ->assertSuccessful();
});
