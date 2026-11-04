<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\Novel;
use App\Models\PodcastComment;
use App\Models\Quote;
use App\Models\Story;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the site overview for administrators.
     */
    public function index(): View
    {
        return view('admin.dashboard', [
            'page' => 'Admin',
            'stats' => [
                'المستخدمون' => User::count(),
                'مستخدمون جدد هذا الأسبوع' => User::where('created_at', '>=', now()->subWeek())->count(),
                'الاقتباسات' => Quote::where('post_type', 'text')->count(),
                'حلقات البودكاست' => Quote::where('post_type', 'audio')->count(),
                'القصص' => Story::count(),
                'الروايات' => Novel::count(),
                'الإعجابات' => Like::count(),
                'المفضلة' => Favorite::count(),
                'التعليقات' => PodcastComment::count(),
            ],
            'latestUsers' => User::latest()->take(5)->get(),
            'latestQuotes' => Quote::with('user')->latest()->take(5)->get(),
        ]);
    }
}
