<?php

use App\Models\User;

it('shows the designed home page instead of placeholder text', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertViewIs('welcome')
        ->assertDontSee('Sard is working');
});

it('shows the latest text quotes and audio episodes', function () {
    $user = User::factory()->create();

    $user->quotes()->create([
        'post_type' => 'text',
        'text' => 'A quote for the home page',
    ]);

    $user->quotes()->create([
        'post_type' => 'audio',
        'title' => 'An episode for the home page',
        'audio_path' => 'quote-audios/episode.mp3',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('A quote for the home page')
        ->assertSee('An episode for the home page');
});
