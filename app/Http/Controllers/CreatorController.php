<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $creators = User::query()
            ->select([
                'id',
                'name',
                'nickname',
                'avatar',
                'creator_specialties',
                'creator_bio',
                'show_in_creator_space',
                'created_at',
            ])
            ->where('show_in_creator_space', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nickname', 'like', "%{$search}%")
                      ->orWhere('creator_bio', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('creators.index', compact('creators', 'search'));
    }

    public function show(User $user)
    {
        abort_unless($user->show_in_creator_space, 404);

        $user->load([
            'quotes' => function ($query) {
                $query->latest();
            }
        ]);

        return view('creators.show', compact('user'));
    }
}
