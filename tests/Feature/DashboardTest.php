<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('can render page', function () {
    $this
        ->get('/')
        ->assertSuccessful();
});
