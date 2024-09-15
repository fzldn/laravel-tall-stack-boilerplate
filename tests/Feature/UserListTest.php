<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    createAndAssignRole($this->user, Permission::USERS_VIEWANY);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(UserResource::getUrl('index'))
        ->assertSuccessful();
});
