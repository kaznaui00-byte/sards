
@extends('Layout.AuthImg')

@section('title', 'إنشاء حساب | سرد')

@section('form')

<div class="signup-card" dir="rtl">

    <div class="signup-logo">
        <img src="{{ asset('images/Untitled-1.png') }}" alt="سرد">
    </div>

    <h2>انضمي إلى سرد</h2>

    <p class="signup-description">
        أنشئي حسابك وابدئي حكايتك معنا
    </p>

    @if ($errors->any())
        <div class="signup-errors">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('signup.store') }}" method="POST">
        @csrf

        <div class="signup-field">
            <label for="name">الاسم</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                minlength="2"
                maxlength="50"
                required
                autocomplete="name"
                placeholder="اكتبي اسمك"
            >
        </div>

        <div class="signup-field">
            <label for="email">البريد الإلكتروني</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="example@email.com"
            >
        </div>

        <div class="signup-field">
            <label for="password">كلمة المرور</label>
            <input
                id="password"
                name="password"
                type="password"
                minlength="8"
                required
                autocomplete="new-password"
                placeholder="8 أحرف على الأقل"
            >
        </div>

        <button type="submit" class="signup-submit">
            إنشاء حساب
        </button>

    </form>

    <div class="signup-footer">
        لديك حساب بالفعل؟
        <a href="{{ route('login') }}">تسجيل الدخول</a>
    </div>

</div>

<style>
.signup-card {
    width: 100%;
    max-width: 460px;
    margin: 35px auto;
    padding: 38px;
    background: #fff;
    border: 1px solid #eee9e2;
    border-radius: 16px;
    box-shadow: 0 8px 35px rgba(0,0,0,0.05);
    box-sizing: border-box;
}

.signup-logo {
    display: flex;
    justify-content: center;
    margin-bottom: 18px;
}

.signup-logo img {
    width: 105px;
    height: 55px;
    object-fit: contain;
}

.signup-card h2 {
    margin: 0;
    text-align: center;
    color: #28231f;
    font-size: 26px;
    font-weight: 800;
}

.signup-description {
    margin: 10px 0 28px;
    text-align: center;
    color: #888078;
    font-size: 14px;
}

.signup-field {
    margin-bottom: 19px;
}

.signup-field label {
    display: block;
    margin-bottom: 9px;
    color: #39332e;
    font-size: 14px;
    font-weight: 700;
}

.signup-field input {
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

.signup-field input:focus {
    border-color: #9b8060;
    box-shadow: 0 0 0 3px rgba(155,128,96,0.12);
}

.signup-submit {
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

.signup-submit:hover {
    background: #514236;
}

.signup-footer {
    margin-top: 25px;
    text-align: center;
    color: #777;
    font-size: 14px;
}

.signup-footer a {
    color: #8b6c4d;
    font-weight: 700;
    text-decoration: none;
}

.signup-footer a:hover {
    text-decoration: underline;
}

.signup-errors {
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 8px;
    background: #fff0f0;
    color: #b42318;
    font-size: 13px;
}

@media (max-width: 500px) {
    .signup-card {
        padding: 26px 20px;
        margin: 20px auto;
    }
}
</style>

@endsection
