@extends('Layout.AuthImg')

@section('title', 'استعادة كلمة المرور | سرد')

@section('form')
<form action="{{ route('password.email') }}" method="POST">
    @csrf

    <h2>استعادة كلمة المرور</h2>

    <p class="mb-5 text-center text-white">
        أدخلي بريدك الإلكتروني لإرسال رابط إعادة التعيين.
    </p>

    @if(session('status'))
        <p class="mb-4 text-green-300">{{ session('status') }}</p>
    @endif

    <label for="email">البريد الإلكتروني</label>
    <input id="email" name="email" type="email"
           value="{{ old('email') }}" required>

    @error('email')
        <p class="text-red-400">{{ $message }}</p>
    @enderror

    <button type="submit" class="btn-primary">
        إرسال رابط الاستعادة
    </button>

    <p class="mt-5 text-center">
        <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
    </p>
</form>
@endsection