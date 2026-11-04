<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    // عرض القصص وتصنيفها
    public function index(Request $request)
    {
        $stories = Story::with('user')

            ->when($request->filter === 'short', function ($query) {
                $query->where('story_type', 'short_story');
            })

            ->when($request->filter === 'long', function ($query) {
                $query->where('story_type', 'long_story');
            })

            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('sections.stories', compact('stories'));
    }

    // عرض تفاصيل القصة
    public function show(Story $story)
    {
        $story->load('user');

        return view('sections.story-details', compact('story'));
    }

    // نشر قصة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'story_type' => ['required', 'in:short_story,long_story'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'content' => ['required', 'string', 'min:3'],
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_path'] = $request
                ->file('cover_image')
                ->store('story-covers', 'public');
        }

        unset($validated['cover_image']);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'published';

        Story::create($validated);

        return redirect()
            ->route('stories.index')
            ->with('success', 'تم نشر قصتك بنجاح.');
    }

    // حذف القصة من قِبل صاحبها فقط
    public function destroy(Story $story)
    {
        abort_unless(
            (int) $story->user_id === (int) auth()->id(),
            403
        );

        if ($story->cover_path) {
            Storage::disk('public')->delete($story->cover_path);
        }

        $story->delete();

        return redirect()
            ->route('stories.index')
            ->with('success', 'تم حذف القصة.');
    }
}
