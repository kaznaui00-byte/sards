<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('quote.user')
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Quote $quote)
    {
        $user = auth()->user();

        $record = Favorite::where('user_id', $user->id)
            ->where('quote_id', $quote->id)
            ->first();

        if ($record) {
            $record->delete();
            $favorited = false;
            $message = 'تمت إزالة الاقتباس من المفضلة.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'quote_id' => $quote->id,
            ]);
            $favorited = true;
            $message = 'تم حفظ الاقتباس في المفضلة.';
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return redirect()->route('quotes.index')->with('success', $message);
    }
}
