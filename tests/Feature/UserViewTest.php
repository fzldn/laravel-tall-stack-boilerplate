<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

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
    $user = User::factory()->create();

    $this
        ->get(UserResource::getUrl('view', ['record' => $user]))
        ->assertSuccessful();
});
