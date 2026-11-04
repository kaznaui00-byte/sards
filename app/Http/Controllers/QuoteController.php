<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $quotes = Quote::with([
            'user',
            'likes',
            'reposts.user',
        ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('text', 'like', '%'.$search.'%')
                        ->orWhere('title', 'like', '%'.$search.'%')
                        ->orWhere('attribution', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->get();

        return view('quotes.index', compact('quotes', 'search'));
    }

    public function store(StoreQuoteRequest $request)
    {
        $data = $request->validated();

        // هذه الصفحة مخصصة للاقتباسات النصية فقط
        if ($data['post_type'] !== 'text') {
            abort(403, 'هذه الصفحة مخصصة للاقتباسات النصية فقط.');
        }

        $request->user()->quotes()->create([
            'text' => $data['text'],
            'attribution' => $request->input('attribution'),
            'post_type' => 'text',
            'title' => null,
            'audio_path' => null,
        ]);

        return redirect()
            ->route('quotes.index')
            ->with('success', 'تم نشر الاقتباس بنجاح!');
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reposted' => $reposted,
                'reposts_count' => $quote->reposts()->count(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function toggleLike(Request $request, Quote $quote)
    {
        $like = $quote->likes()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
            $message = 'تم إلغاء الإعجاب.';
        } else {
            $quote->likes()->create([
                'user_id' => $request->user()->id,
            ]);

            $liked = true;
            $message = 'تم تسجيل الإعجاب.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $quote->likes()->count(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function toggleRepost(Request $request, Quote $quote)
    {
        $repost = $quote->reposts()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($repost) {
            $repost->delete();
            $reposted = false;
            $message = 'تم إلغاء إعادة النشر.';
        } else {
            $quote->reposts()->create([
                'user_id' => $request->user()->id,
            ]);

            $reposted = true;
            $message = 'تمت إعادة النشر بنجاح.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reposted' => $reposted,
                'reposts_count' => $quote->reposts()->count(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function edit(Quote $quote)
    {
        abort_unless($quote->user_id === auth()->id(), 403);

        return view('quotes.edit', compact('quote'));
    }

    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        abort_unless($quote->user_id === auth()->id(), 403);

        $quote->update($request->validated());

        return redirect()->route('quotes.index')
            ->with('success', 'تم تحديث الاقتباس.');
    }

    public function destroy(Quote $quote)
    {
        abort_unless($quote->user_id === auth()->id(), 403);

        if ($quote->audio_path) {
            Storage::disk('public')->delete($quote->audio_path);
        }

        $quote->delete();

        return back()->with('success', 'تم حذف المنشور.');
    }
}
