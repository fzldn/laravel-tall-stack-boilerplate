<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermissions($this->user, [
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
