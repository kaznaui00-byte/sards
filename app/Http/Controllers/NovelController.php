<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovelController extends Controller
{
    // عرض الروايات وتصنيفها
    public function index(Request $request)
    {
        $novels = Novel::with('user')
            ->withCount('chapters')
            ->where('is_published', true)

            ->when($request->filter === 'short', function ($query) {
                $query->where('length_type', 'short');
            })

            ->when($request->filter === 'long', function ($query) {
                $query->where('length_type', 'long');
            })

            ->when($request->filter === 'completed', function ($query) {
                $query->where('status', 'completed');
            })

            ->when($request->filter === 'ongoing', function ($query) {
                $query->where('status', 'ongoing');
            })

            ->when($request->genre, function ($query, $genre) {
                $query->where('genre', $genre);
            })

            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('sections.novels', compact('novels'));
    }

    // تفاصيل الرواية وفصولها
    public function show(Novel $novel)
    {
        abort_unless($novel->is_published, 404);

        $novel->load([
            'user',
            'chapters' => function ($query) {
                $query->where('is_published', true)
                    ->orderBy('chapter_number');
            },
        ]);

        return view('sections.novel-details', compact('novel'));
    }

    // إضافة رواية
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'genre' => ['required', 'string', 'max:50'],
            'length_type' => ['required', 'in:short,long'],
            'status' => ['required', 'in:ongoing,completed'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image_path'] = $request
                ->file('cover_image')
                ->store('novel-covers', 'public');
        }

        unset($validated['cover_image']);

        $validated['user_id'] = auth()->id();
        $validated['is_published'] = true;

        Novel::create($validated);

        return redirect()
            ->route('novels.index')
            ->with('success', 'تمت إضافة الرواية بنجاح.');
    }

    // حذف الرواية
    public function destroy(Novel $novel)
    {
        abort_unless($novel->user_id === auth()->id() || auth()->user()->is_admin, 403);

        if ($novel->cover_image_path) {
            Storage::disk('public')->delete($novel->cover_image_path);
        }

        $novel->delete();

        return redirect()
            ->route('novels.index')
            ->with('success', 'تم حذف الرواية.');
    }
}
