<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChapterController extends Controller
{
    /**
     * Show a chapter for reading, with links to its neighbors.
     */
    public function show(Novel $novel, Chapter $chapter): View
    {
        abort_unless($novel->is_published && $chapter->is_published, 404);

        $chapters = $novel->chapters()->where('is_published', true);

        return view('sections.chapter', [
            'novel' => $novel,
            'chapter' => $chapter,
            'previousChapter' => (clone $chapters)->where('chapter_number', '<', $chapter->chapter_number)->orderByDesc('chapter_number')->first(),
            'nextChapter' => (clone $chapters)->where('chapter_number', '>', $chapter->chapter_number)->orderBy('chapter_number')->first(),
        ]);
    }

    /**
     * Add the next chapter to a novel.
     */
    public function store(Request $request, Novel $novel): RedirectResponse
    {
        $this->authorizeNovelOwner($request, $novel);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string', 'min:3'],
        ]);

        $novel->chapters()->create([
            ...$validated,
            'chapter_number' => (int) $novel->chapters()->max('chapter_number') + 1,
            'is_published' => true,
        ]);

        return redirect()->route('novels.show', $novel)->with('success', 'تمت إضافة الفصل.');
    }

    /**
     * Delete a chapter.
     */
    public function destroy(Request $request, Novel $novel, Chapter $chapter): RedirectResponse
    {
        $this->authorizeNovelOwner($request, $novel);

        $chapter->delete();

        return redirect()->route('novels.show', $novel)->with('success', 'تم حذف الفصل.');
    }

    /**
     * Only the author or an administrator may change a novel.
     */
    private function authorizeNovelOwner(Request $request, Novel $novel): void
    {
        abort_unless($novel->user_id === $request->user()->id || $request->user()->is_admin, 403);
    }
}
