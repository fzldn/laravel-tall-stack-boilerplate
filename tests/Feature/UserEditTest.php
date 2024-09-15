<?php

use App\Enums\Permission;
use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Livewire\livewire;

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

it('can retrieve data', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('can save', function () {
    $user = User::factory()->create();
    $data = User::factory()->make();

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => $data->name,
            'email' => $data->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'id' => $user->id,
        'name' => $data->name,
        'email' => $data->email,
    ]);
});

it('can validate input', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => null,
            'email' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
        ]);
});

it('cannot edit with same email', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'email' => $this->user->email,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'email' => 'unique',
        ]);
});
