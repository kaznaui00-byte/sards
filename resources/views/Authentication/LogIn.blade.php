
@extends('Layout.app')

@section('title', 'تسجيل الدخول')

@section('content')

<div class="login-page" dir="rtl">

    <div class="login-card">

        <div class="login-logo">
            <img src="{{ asset('images/Untitled-1.png') }}" alt="سرد">
        </div>

        <h1>مرحبًا بعودتك</h1>

        <p class="login-description">
            سجّل دخولك واكتشف عالم سرد
        </p>

        @if ($errors->any())
            <div class="login-errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <div class="login-field">
                <label for="email">البريد الإلكتروني</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="أدخل بريدك الإلكتروني"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="login-field">
                <label for="password">كلمة المرور</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="أدخل كلمة المرور"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="login-submit">
                تسجيل الدخول
            </button>
        </form>

        <div class="login-footer">
            ليس لديك حساب؟
            <a href="{{ route('signup') }}">إنشاء حساب جديد</a>
        </div>

    </div>
</div>

<style>
.login-page {
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 45px 20px;
    background: #faf9f7;
}

.login-card {
    width: 100%;
    max-width: 460px;
    padding: 40px;
    background: #fff;
    border: 1px solid #eee9e2;
    border-radius: 16px;
    box-shadow: 0 8px 35px rgba(0,0,0,0.05);
}

.login-logo {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.login-logo img {
    width: 105px;
    height: 55px;
    object-fit: contain;
}

.login-card h1 {
    margin: 0;
    text-align: center;
    color: #28231f;
    font-size: 27px;
    font-weight: 800;
}

.login-description {
    margin: 10px 0 30px;
    text-align: center;
    color: #888078;
    font-size: 14px;
}

.login-field {
    margin-bottom: 20px;
}

.login-field label {
    display: block;
    margin-bottom: 9px;
    color: #39332e;
    font-size: 14px;
    font-weight: 700;
}

.login-field input {
    width: 100%;
    height: 49px;
    padding: 0 14px;
    border: 1px solid #e4dfd8;
    border-radius: 9px;
    background: #fff;
    color: #28231f;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

.login-field input:focus {
    border-color: #9b8060;
    box-shadow: 0 0 0 3px rgba(155,128,96,0.12);
}

.login-submit {
    width: 100%;
    height: 49px;
    margin-top: 8px;
    border: none;
    border-radius: 9px;
    background: #29231f;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.login-submit:hover {
    background: #514236;
}

.login-footer {
    margin-top: 25px;
    text-align: center;
    color: #777;
    font-size: 14px;
}

.login-footer a {
    color: #8b6c4d;
    font-weight: 700;
    text-decoration: none;
}

.login-footer a:hover {
    text-decoration: underline;
}

.login-errors {
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 8px;
    background: #fff0f0;
    color: #b42318;
    font-size: 13px;
}

@media (max-width: 500px) {
    .login-card {
        padding: 28px 20px;
    }
}
</style>

@endsection
