<?php

use App\Models\Novel;
use App\Models\User;

function publishedNovel(?User $author = null): Novel
{
    return Novel::create([
        'user_id' => ($author ?? User::factory()->create())->id,
        'title' => 'رواية منشورة',
        'genre' => 'أدب',
        'length_type' => 'short',
        'status' => 'ongoing',
        'is_published' => true,
    ]);
}

it('lists published novels and filters them', function () {
    publishedNovel();

    $this->get(route('novels.index'))->assertOk()->assertSee('رواية منشورة');
    $this->get(route('novels.index', ['filter' => 'long']))->assertOk()->assertDontSee('رواية منشورة');
});

it('shows the add novel form only to signed in users', function () {
    $this->get(route('novels.index'))->assertDontSee('أضف روايتك');

    $this->actingAs(User::factory()->create())
        ->get(route('novels.index'))
        ->assertSee('أضف روايتك');
});

it('lets a signed in user publish a novel', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('novels.store'), [
            'title' => 'رواية جديدة',
            'genre' => 'تاريخ',
            'length_type' => 'long',
            'status' => 'ongoing',
        ])
        ->assertRedirect(route('novels.index'));

    $this->get(route('novels.index'))->assertSee('رواية جديدة');
});

it('numbers chapters and lets readers open them in order', function () {
    $author = User::factory()->create();
    $novel = publishedNovel($author);

    $this->actingAs($author);

    $this->post(route('novels.chapters.store', $novel), ['title' => 'البداية', 'content' => 'نص الفصل الأول']);
    $this->post(route('novels.chapters.store', $novel), ['title' => 'النهاية', 'content' => 'نص الفصل الثاني']);

    [$first, $second] = $novel->chapters()->orderBy('chapter_number')->get();

    expect($first->chapter_number)->toBe(1)->and($second->chapter_number)->toBe(2);

    $this->get(route('novels.show', $novel))->assertOk()->assertSee('البداية')->assertSee('النهاية');

    $this->get(route('novels.chapters.show', [$novel, $first]))
        ->assertOk()
        ->assertSee('نص الفصل الأول')
        ->assertSee(route('novels.chapters.show', [$novel, $second]));
});

it('stops other users from changing a novel', function () {
    $novel = publishedNovel();
    $chapter = $novel->chapters()->create(['title' => 'فصل', 'content' => 'نص', 'chapter_number' => 1, 'is_published' => true]);

    $this->actingAs(User::factory()->create());

    $this->post(route('novels.chapters.store', $novel), ['title' => 'دخيل', 'content' => 'نص دخيل'])->assertForbidden();
    $this->delete(route('novels.chapters.destroy', [$novel, $chapter]))->assertForbidden();
    $this->delete(route('novels.destroy', $novel))->assertForbidden();
});

it('does not open a chapter through another novel', function () {
    $chapter = publishedNovel()->chapters()->create(['title' => 'فصل', 'content' => 'نص', 'chapter_number' => 1, 'is_published' => true]);

    $this->get(route('novels.chapters.show', [publishedNovel(), $chapter]))->assertNotFound();
});

it('lets users comment on episodes and delete their own comments only', function () {
    $author = User::factory()->create();
    $episode = $author->quotes()->create(['title' => 'حلقة', 'post_type' => 'audio', 'audio_path' => 'quote-audios/a.mp3']);

    $this->actingAs($author)->post(route('podcasts.comments.store', $episode), ['body' => 'رد جميل']);

    $this->get(route('podcasts.index'))->assertOk()->assertSee('رد جميل');

    $comment = $episode->comments()->firstOrFail();

    $this->actingAs(User::factory()->create())
        ->delete(route('podcasts.comments.destroy', $comment))
        ->assertForbidden();

    $this->actingAs($author)->delete(route('podcasts.comments.destroy', $comment))->assertRedirect();

    expect($comment->fresh())->toBeNull();
});

it('lets authors delete their own podcast episode', function () {
    $author = User::factory()->create();
    $episode = $author->quotes()->create(['title' => 'حلقة للحذف', 'post_type' => 'audio', 'audio_path' => 'quote-audios/a.mp3']);

    $this->actingAs($author)->get(route('podcasts.index'))->assertSee('حذف الحلقة');

    $this->actingAs(User::factory()->create())->get(route('podcasts.index'))->assertDontSee('حذف الحلقة');

    $this->actingAs($author)->delete(route('quotes.destroy', $episode))->assertRedirect();

    expect($episode->fresh())->toBeNull();
});

it('uses the Sard name in page titles', function () {
    $this->get(route('quotes.index'))->assertSee('الاقتباسات | سرد')->assertDontSee('QuoteIt');
});

it('lets an administrator create a working password reset link', function () {
    $user = User::factory()->create();
    $admin = tap(User::factory()->create())->forceFill(['is_admin' => true]);
    $admin->save();

    $this->actingAs($admin)
        ->post(route('admin.users.reset-link', $user))
        ->assertSessionHas('reset_link');

    $url = session('reset_link.url');

    auth()->logout();

    $this->get($url)->assertOk();
});

it('tells users honestly when password email is unavailable in production', function () {
    app()['env'] = 'production';
    config(['mail.default' => 'log']);

    $user = User::factory()->create();

    $this->withSession(['_token' => 'test-token'])
        ->post(route('password.email'), ['_token' => 'test-token', 'email' => $user->email])
        ->assertSessionHasErrors('email');
});
