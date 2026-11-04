<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\SignLanguageController;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

// ==========================================
// HOME PAGE - PUBLIC BROWSING
// ==========================================

Route::get('/', fn () => 'Sard is working')->name('home');

// ==========================================
// SIGN UP
// ==========================================

Route::get('/signup', function () {
    return view('Authentication.SignUp', [
        'page' => 'Sign Up'
    ]);
})->name('signup');

Route::post('/signup', function (Request $request) {

    $validated = $request->validate([
        'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{L} ]+$/u'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    $request->session()->regenerate();

    return redirect()->route('quotes.index');

})->name('signup.store');


// ==========================================
// LOGIN
// ==========================================

Route::get('/login', function () {
    return view('Authentication.LogIn', [
        'page' => 'Log In'
    ]);
})->name('login');

Route::post('/login', function (LoginRequest $request) {

    $credentials = $request->validated();

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return redirect()->intended(route('quotes.index'));
    }

    return back()->withErrors([
        'email' => 'The email or password is incorrect.',
    ])->onlyInput('email');

})->name('login.store');

// FORGOT PASSWORD

Route::get('/forgot-password', function () {
    return view('Authentication.ForgotPassword');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::ResetLinkSent
        ? back()->with('status', 'تم إرسال رابط استعادة كلمة المرور.')
        : back()->withErrors([
            'email' => 'تعذر إرسال الرابط. تحققي من البريد الإلكتروني.',
        ]);

})->middleware('guest')->name('password.email');


// RESET PASSWORD

Route::get('/reset-password/{token}', function (string $token) {
    return view('Authentication.ResetPassword', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),
        function (User $user, string $password) {

            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PasswordReset
        ? redirect()->route('login')->with(
            'status',
            'تم تغيير كلمة المرور بنجاح.'
        )
        : back()->withErrors([
            'email' => 'رابط الاستعادة غير صالح أو انتهت صلاحيته.',
        ]);

})->middleware('guest')->name('password.update');
// ==========================================
// LOGOUT
// ==========================================

Route::post('/logout', function (\Illuminate\Http\Request $request) {

    \Illuminate\Support\Facades\Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('quotes.index');

})->name('logout');

// ==========================================
// PROFILE
// ==========================================

Route::get('/profile', function () {

    return view('profile.index', [
        'page' => 'Profile'
    ]);

})->middleware('auth')->name('profile');


// UPDATE PROFILE AND CREATOR INFORMATION

Route::put('/profile', function (Request $request) {

    $user = $request->user();

    $specialties = [
        'writer',
        'novelist',
        'storyteller',
        'podcaster',
        'quote_creator',
        'sign_language_creator',
        'accessible_content_creator',
        'artist',
    ];

    $validated = $request->validate([

        'name' => [
            'required',
            'string',
            'min:2',
            'max:50'
        ],

        'nickname' => [
            'nullable',
            'string',
            'max:50'
        ],

        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($user->id),
        ],

        'birthdate' => [
            'nullable',
            'date_format:Y-m-d',
            'before_or_equal:today',
        ],

        'avatar' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ],

        'creator_specialties' => [
            'nullable',
            'array',
            'max:5',
        ],

        'creator_specialties.*' => [
            'required',
            Rule::in($specialties),
        ],

        'creator_bio' => [
            'nullable',
            'string',
            'max:500',
        ],

        'show_in_creator_space' => [
            'nullable',
            'boolean',
        ],

    ]);

    // Personal information

    $user->name = $validated['name'];

    $user->nickname = $validated['nickname'] ?? null;

    $user->email = $validated['email'];

    $user->birthdate = $validated['birthdate'] ?? null;


    // Creator information

    $user->creator_specialties =
        $validated['creator_specialties'] ?? [];

    $user->creator_bio =
        $validated['creator_bio'] ?? null;

    $user->show_in_creator_space =
        $request->boolean('show_in_creator_space');


    // Profile picture

    $oldAvatar = $user->avatar;

    $newAvatarUploaded = $request->hasFile('avatar');

    if ($newAvatarUploaded) {

        $user->avatar = $request->file('avatar')
            ->store('avatars', 'public');
    }

    $user->save();

    if ($newAvatarUploaded && $oldAvatar) {

        Storage::disk('public')->delete($oldAvatar);
    }

    return redirect()
        ->route('profile')
        ->with('success', 'Profile updated successfully!');

})->middleware('auth')->name('profile.update');


// ==========================================
// QUOTES AND AUDIO POSTS
// ==========================================

// Public browsing

Route::get('/quotes', [QuoteController::class, 'index'])
    ->name('quotes.index');

// Publishing

Route::post('/quotes', [QuoteController::class, 'store'])
    ->middleware('auth')
    ->name('quotes.store');

// Likes

Route::post('/quotes/{quote}/like', [QuoteController::class, 'toggleLike'])
    ->middleware('auth')
    ->name('quotes.like');

Route::post('/quotes/{quote}/repost', [QuoteController::class, 'toggleRepost'])
    ->middleware('auth')
    ->name('quotes.repost');


// Editing

Route::get('/quotes/{quote}/edit', [QuoteController::class, 'edit'])
    ->middleware('auth')
    ->name('quotes.edit');

Route::put('/quotes/{quote}', [QuoteController::class, 'update'])
    ->middleware('auth')
    ->name('quotes.update');

// Deleting

Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])
    ->middleware('auth')
    ->name('quotes.destroy');


// ==========================================
// FAVORITES
// ==========================================

Route::get('/favorites', [FavoriteController::class, 'index'])
    ->middleware('auth')
    ->name('favorites.index');

Route::post('/quotes/{quote}/favorite', [FavoriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('quotes.favorite');


// ==========================================
// ADMIN - SIGN LANGUAGE
// ==========================================

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/sign-language', [
            SignLanguageController::class,
            'index'
        ])->name('sign-language.index');

        Route::post('/sign-language/{quote}', [
            SignLanguageController::class,
            'store'
        ])->name('sign-language.store');

        Route::delete('/sign-language/{quote}', [
            SignLanguageController::class,
            'destroy'
        ])->name('sign-language.destroy');

    });


// Sign language translator
Route::get('/sign-language', function () {
    return redirect('/sign-language-app/');
})->name('sign-language.translator');

// Public Creator Directory
Route::get('/creators', [
    \App\Http\Controllers\CreatorController::class,
    'index'
])->name('creators.index');

Route::get('/creators/{user}', [
    \App\Http\Controllers\CreatorController::class,
    'show'
])->name('creators.show');

// Public Podcast Page
Route::get('/podcasts', [
    \App\Http\Controllers\PodcastController::class,
    'index'
])->name('podcasts.index');
Route::post('/podcasts', [PodcastController::class, 'store'])
    ->middleware('auth')
    ->name('podcasts.store');
// قسم الروايات

Route::get('/novels', [
    \App\Http\Controllers\NovelController::class,
    'index'
])->name('novels.index');

Route::get('/novels/{novel}', [
    \App\Http\Controllers\NovelController::class,
    'show'
])->name('novels.show');

Route::post('/novels', [
    \App\Http\Controllers\NovelController::class,
    'store'
])->middleware('auth')->name('novels.store');

Route::delete('/novels/{novel}', [
    \App\Http\Controllers\NovelController::class,
    'destroy'
])->middleware('auth')->name('novels.destroy');
// قسم القصص

Route::get('/stories', [
    \App\Http\Controllers\StoryController::class,
    'index'
])->name('stories.index');

Route::get('/stories/{story}', [
    \App\Http\Controllers\StoryController::class,
    'show'
])->name('stories.show');

Route::post('/stories', [
    \App\Http\Controllers\StoryController::class,
    'store'
])->middleware('auth')->name('stories.store');

Route::delete('/stories/{story}', [
    \App\Http\Controllers\StoryController::class,
    'destroy'
])->middleware('auth')->name('stories.destroy');
Route::post('/podcasts/{quote}/comments', [
    \App\Http\Controllers\PodcastController::class,
    'storeComment'
])->middleware('auth')->name('podcasts.comments.store');
