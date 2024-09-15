<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, Permission::USERS_VIEWANY);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(UserResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list users', function () {
    $users = User::factory(2)->create();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users);
});
