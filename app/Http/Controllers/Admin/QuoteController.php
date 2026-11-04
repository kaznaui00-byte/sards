<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class QuoteController extends Controller
{
    /**
     * List every post with search and type filters.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $type = $request->string('type')->toString();

        $quotes = Quote::with('user')
            ->withCount(['likes', 'reposts'])
            ->when(in_array($type, ['text', 'audio'], true), fn ($query) => $query->where('post_type', $type))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('text', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('attribution', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotes.index', [
            'page' => 'Admin',
            'quotes' => $quotes,
            'search' => $search,
            'type' => $type,
        ]);
    }

    /**
     * Show the form for adding a quote.
     */
    public function create(): View
    {
        return view('admin.quotes.form', ['page' => 'Admin', 'quote' => new Quote]);
    }

    /**
     * Publish a new quote as the current administrator.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->user()->quotes()->create([
            ...$this->validatedQuote($request),
            'post_type' => 'text',
        ]);

        return redirect()->route('admin.quotes.index')->with('success', 'تم نشر الاقتباس.');
    }

    /**
     * Show the form for editing any post.
     */
    public function edit(Quote $quote): View
    {
        return view('admin.quotes.form', ['page' => 'Admin', 'quote' => $quote]);
    }

    /**
     * Save changes to any post.
     */
    public function update(Request $request, Quote $quote): RedirectResponse
    {
        $validated = $quote->post_type === 'audio'
            ? $request->validate(['title' => ['required', 'string', 'max:100']])
            : $this->validatedQuote($request);

        $quote->update($validated);

        return redirect()->route('admin.quotes.index')->with('success', 'تم حفظ التعديل.');
    }

    /**
     * Delete any post along with its uploaded files.
     */
    public function destroy(Quote $quote): RedirectResponse
    {
        foreach (array_filter([$quote->audio_path, $quote->sign_language_video_path]) as $path) {
            Storage::disk('public')->delete($path);
        }

        $quote->delete();

        return back()->with('success', 'تم حذف المنشور.');
    }

    /**
     * Validate the editable fields of a text quote.
     *
     * @return array{text: string, attribution: string|null}
     */
    private function validatedQuote(Request $request): array
    {
        return $request->validate([
            'text' => ['required', 'string', 'min:3', 'max:155'],
            'attribution' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
