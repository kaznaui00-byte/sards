<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * List users with search.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $users = User::withCount('quotes')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', ['page' => 'Admin', 'users' => $users, 'search' => $search]);
    }

    /**
     * Grant or remove administrator access for another user.
     */
    public function toggleAdmin(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'لا يمكنك تغيير صلاحية حسابك.');
        }

        $user->forceFill(['is_admin' => ! $user->is_admin])->save();

        return back()->with('success', 'تم تحديث الصلاحية.');
    }

    /**
     * Create a one-time password reset link for the administrator to pass on.
     */
    public function resetLink(User $user): RedirectResponse
    {
        return back()->with('reset_link', [
            'name' => $user->name,
            'url' => route('password.reset', [
                'token' => Password::createToken($user),
                'email' => $user->email,
            ]),
        ]);
    }

    /**
     * Delete another user and their content.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'لا يمكنك حذف حسابك من لوحة التحكم.');
        }

        $user->delete();

        return back()->with('success', 'تم حذف المستخدم ومحتواه.');
    }
}
