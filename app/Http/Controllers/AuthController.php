
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome', ['page' => 'Home']);
})->name('home');

Route::get('/signup', function () {
    return view('Authentication.SignUp', ['page' => 'Sign Up']);
})->name('signup');

Route::post('/signup', [AuthController::class, 'register'])
    ->name('signup.store');

Route::get('/login', function () {
    return view('Authentication.LogIn', ['page' => 'Log In']);
})->name('login');

Route::get('/logout', function () {
    return redirect()->route('home');
});
