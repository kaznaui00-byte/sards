<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignLanguageController extends Controller
{
    /**
     * Display all posts in the admin dashboard.
     */
    public function index()
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $quotes = Quote::with('user')
            ->latest()
            ->get();

        return view('admin.sign-language.index', compact('quotes'));
    }

    /**
     * Upload or replace a sign language video.
     */
    public function store(Request $request, Quote $quote)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $validated = $request->validate([
            'video' => [
                'required',
                'file',
                'mimes:mp4,mov,webm',
                'max:4096',
            ],
        ]);

        $newPath = $request->file('video')
            ->store('sign-language', 'public');

        if (! $newPath) {
            return back()->withErrors([
                'video' => 'Unable to upload the video.',
            ]);
        }

        if ($quote->sign_language_video_path) {
            Storage::disk('public')->delete(
                $quote->sign_language_video_path
            );
        }

        $quote->update([
            'sign_language_video_path' => $newPath,
        ]);

        return redirect()
            ->route('admin.sign-language.index')
            ->with('success', 'Sign language video uploaded successfully!');
    }

    /**
     * Delete a sign language video.
     */
    public function destroy(Quote $quote)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        if ($quote->sign_language_video_path) {
            Storage::disk('public')->delete(
                $quote->sign_language_video_path
            );

            $quote->update([
                'sign_language_video_path' => null,
            ]);
        }

        return redirect()
            ->route('admin.sign-language.index')
            ->with('success', 'Sign language video deleted successfully!');
    }
}
