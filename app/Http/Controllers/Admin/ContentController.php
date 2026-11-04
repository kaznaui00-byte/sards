<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\Story;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    /**
     * List stories and novels.
     */
    public function index(): View
    {
        return view('admin.content.index', [
            'page' => 'Admin',
            'stories' => Story::with('user')->latest()->paginate(20, ['*'], 'stories_page'),
            'novels' => Novel::with('user')->latest()->paginate(20, ['*'], 'novels_page'),
        ]);
    }

    /**
     * Delete a story and its cover.
     */
    public function destroyStory(Story $story): RedirectResponse
    {
        if ($story->cover_path) {
            Storage::disk('public')->delete($story->cover_path);
        }

        $story->delete();

        return back()->with('success', 'تم حذف القصة.');
    }

    /**
     * Delete a novel and its cover.
     */
    public function destroyNovel(Novel $novel): RedirectResponse
    {
        if ($novel->cover_image_path) {
            Storage::disk('public')->delete($novel->cover_image_path);
        }

        $novel->delete();

        return back()->with('success', 'تم حذف الرواية.');
    }
}
