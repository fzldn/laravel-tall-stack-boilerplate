<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    givePermission($this->user, [
        Permission::USERS_VIEWANY,
        Permission::USERS_CREATE,
    ]);

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get(UserResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create', function () {
    $data = User::factory()->make();

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => $data->name,
            'email' => $data->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $data->name,
        'email' => $data->email,
    ]);
});

it('can validate input', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'password' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
});

it('cannot create with same email', function () {
    $data = User::factory()->make(['email' => $this->user->email]);

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => $data->name,
            'email' => $data->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'unique',
        ]);
});
