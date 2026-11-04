<?php

use App\Models\Novel;
use App\Models\SiteAppearance;
use App\Models\Story;
use App\Models\User;

function adminUser(): User
{
    return tap(User::factory()->create())->forceFill(['is_admin' => true]);
}

function savedAdmin(): User
{
    return tap(adminUser())->save();
}

it('redirects guests to the login page', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

it('blocks regular users from every admin section', function (string $routeName) {
    $this->actingAs(User::factory()->create())
        ->get(route($routeName))
        ->assertForbidden();
})->with([
    'admin.dashboard',
    'admin.quotes.index',
    'admin.users.index',
    'admin.content.index',
    'admin.appearance.edit',
    'admin.sign-language.index',
]);

it('lets administrators open every admin section', function (string $routeName) {
    $this->actingAs(savedAdmin())
        ->get(route($routeName))
        ->assertOk();
})->with([
    'admin.dashboard',
    'admin.quotes.index',
    'admin.quotes.create',
    'admin.users.index',
    'admin.content.index',
    'admin.appearance.edit',
]);

it('lets administrators add, edit and delete a quote', function () {
    $admin = savedAdmin();

    $this->actingAs($admin)
        ->post(route('admin.quotes.store'), ['text' => 'اقتباس جديد', 'attribution' => 'شاعر'])
        ->assertRedirect(route('admin.quotes.index'));

    $quote = $admin->quotes()->firstOrFail();

    expect($quote->post_type)->toBe('text');

    $this->put(route('admin.quotes.update', $quote), ['text' => 'اقتباس معدل', 'attribution' => null])
        ->assertRedirect(route('admin.quotes.index'));

    expect($quote->fresh()->text)->toBe('اقتباس معدل');

    $this->delete(route('admin.quotes.destroy', $quote))->assertRedirect();

    expect($quote->fresh())->toBeNull();
});

it('lets administrators edit posts that belong to other users', function () {
    $quote = User::factory()->create()->quotes()->create(['text' => 'نص قديم', 'post_type' => 'text']);

    $this->actingAs(savedAdmin())
        ->put(route('admin.quotes.update', $quote), ['text' => 'نص جديد'])
        ->assertRedirect();

    expect($quote->fresh()->text)->toBe('نص جديد');
});

it('rejects quotes longer than the site limit', function () {
    $this->actingAs(savedAdmin())
        ->post(route('admin.quotes.store'), ['text' => str_repeat('ا', 156)])
        ->assertSessionHasErrors('text');
});

it('does not let regular users change posts through the admin routes', function () {
    $quote = User::factory()->create()->quotes()->create(['text' => 'نص محمي', 'post_type' => 'text']);

    $this->actingAs(User::factory()->create())
        ->delete(route('admin.quotes.destroy', $quote))
        ->assertForbidden();

    expect($quote->fresh())->not->toBeNull();
});

it('does not let administrators remove their own access or account', function () {
    $admin = savedAdmin();

    $this->actingAs($admin)->patch(route('admin.users.toggle-admin', $admin))->assertRedirect();
    $this->delete(route('admin.users.destroy', $admin))->assertRedirect();

    expect($admin->fresh())->not->toBeNull()
        ->and($admin->fresh()->is_admin)->toBeTruthy();
});

it('lets administrators manage other users', function () {
    $user = User::factory()->create();

    $this->actingAs(savedAdmin())->patch(route('admin.users.toggle-admin', $user));

    expect($user->fresh()->is_admin)->toBeTruthy();

    $this->delete(route('admin.users.destroy', $user));

    expect($user->fresh())->toBeNull();
});

it('does not let signup or profile forms grant administrator access', function () {
    $this->post(route('signup.store'), [
        'name' => 'مستخدم',
        'email' => 'someone@example.com',
        'password' => 'Password123!',
        'is_admin' => true,
    ]);

    expect(User::where('email', 'someone@example.com')->first()->is_admin)->toBeFalsy();
});

it('lets administrators delete stories and novels', function () {
    $user = User::factory()->create();
    $story = Story::create(['user_id' => $user->id, 'title' => 'قصة', 'story_type' => 'short_story', 'content' => 'نص']);
    $novel = Novel::create(['user_id' => $user->id, 'title' => 'رواية', 'genre' => 'أدب', 'length_type' => 'short', 'status' => 'ongoing']);

    $this->actingAs(savedAdmin());
    $this->delete(route('admin.content.stories.destroy', $story))->assertRedirect();
    $this->delete(route('admin.content.novels.destroy', $novel))->assertRedirect();

    expect($story->fresh())->toBeNull()->and($novel->fresh())->toBeNull();
});

it('applies saved appearance settings and can restore the original', function () {
    $this->actingAs(savedAdmin())->put(route('admin.appearance.update'), [
        'primary_color' => '#112233',
        'accent_color' => '#445566',
        'background_color' => '#fefefe',
        'text_color' => '#101010',
        'font_family' => 'Arial',
        'content_width' => '1100',
        'card_radius' => '20',
    ])->assertRedirect(route('admin.appearance.edit'));

    $this->get(route('quotes.index'))->assertSee('--site-bg:#fefefe', false);

    $this->delete(route('admin.appearance.reset'))->assertRedirect();

    expect(SiteAppearance::count())->toBe(0);
});

it('promotes an existing account with the console command', function () {
    $user = User::factory()->create();

    $this->artisan('sard:make-admin', ['email' => $user->email])->assertSuccessful();

    expect($user->fresh()->is_admin)->toBeTruthy();
});

it('fails safely when promoting an unknown email', function () {
    $this->artisan('sard:make-admin', ['email' => 'missing@example.com'])->assertFailed();
});
