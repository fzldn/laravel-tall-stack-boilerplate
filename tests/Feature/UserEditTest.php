<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermissions($this->user, [
        Permission::USERS_VIEWANY,
        Permission::USERS_UPDATE,
    ]);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(UserResource::getUrl('edit', ['record' => User::factory()->create()]))
        ->assertSuccessful();
});
