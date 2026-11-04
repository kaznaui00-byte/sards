<?php

namespace App\Http\Controllers;

use App\Models\PodcastComment;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index()
    {
        $episodes = Quote::with(['user', 'likes', 'comments.user'])
            ->where('post_type', 'audio')
            ->whereNotNull('audio_path')
            ->latest()
            ->paginate(12);

        return view('podcasts.index', compact('episodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'audio' => ['required', 'file', 'mimes:mp3,m4a,wav,ogg,webm,mp4', 'max:4096'],
        ]);

        $audioPath = $request->file('audio')
            ->store('quote-audios', 'public');

        $request->user()->quotes()->create([
            'title' => $validated['title'],
            'audio_path' => $audioPath,
            'post_type' => 'audio',
            'text' => null,
        ]);

        return redirect()
            ->route('podcasts.index')
            ->with('success', 'تم نشر الحلقة الصوتية بنجاح!');
    }

    public function storeComment(Request $request, Quote $quote)
    {
        abort_unless(
            $quote->post_type === 'audio' && ! empty($quote->audio_path),
            404
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:500'],
        ]);

        $quote->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'تم نشر ردك بنجاح.');
    }

    /**
     * Delete a comment as its author or an administrator.
     */
    public function destroyComment(Request $request, PodcastComment $comment): RedirectResponse
    {
        abort_unless($comment->user_id === $request->user()->id || $request->user()->is_admin, 403);

        $comment->delete();

        return back()->with('success', 'تم حذف الرد.');
    }
}
