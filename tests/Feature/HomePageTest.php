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

it('renders the quotes and favorites pages when a quote exists', function () {
    $user = User::factory()->create();
    $quote = $user->quotes()->create(['text' => 'اقتباس محفوظ', 'post_type' => 'text']);

    $this->actingAs($user)->post(route('quotes.favorite', $quote));

    $this->get(route('quotes.index'))->assertOk()->assertSee('اقتباس محفوظ');
    $this->get(route('favorites.index'))->assertOk()->assertSee('اقتباس محفوظ');
});

it('shows login links only to guests', function () {
    $this->get(route('quotes.index'))->assertSee('إنشاء حساب');

    $this->actingAs(User::factory()->create())
        ->get(route('quotes.index'))
        ->assertDontSee('إنشاء حساب')
        ->assertSee('خروج');
});

it('shows the admin link only to administrators', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('quotes.index'))->assertDontSee('لوحة التحكم');

    $user->forceFill(['is_admin' => true])->save();

    $this->actingAs($user)->get(route('quotes.index'))->assertSee('لوحة التحكم');
});
