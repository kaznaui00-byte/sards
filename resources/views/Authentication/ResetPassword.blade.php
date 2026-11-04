@extends('Layout.AuthImg')

@section('title', 'تغيير كلمة المرور | سرد')

@section('form')
<form action="{{ route('password.update') }}" method="POST">
    @csrf

    <h2>كلمة مرور جديدة</h2>

    <input type="hidden" name="token" value="{{ $token }}">

    <label for="email">البريد الإلكتروني</label>
    <input id="email" name="email" type="email"
           value="{{ old('email', $email) }}" required>

    <label for="password">كلمة المرور الجديدة</label>
    <input id="password" name="password" type="password"
           minlength="8" required>

    <label for="password_confirmation">تأكيد كلمة المرور</label>
    <input id="password_confirmation"
           name="password_confirmation"
           type="password" minlength="8" required>

    @if($errors->any())
        <div class="text-red-400">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <button type="submit" class="btn-primary">
        حفظ كلمة المرور
    </button>
</form>
<a href="{{ route('password.request') }}">
    نسيتِ كلمة المرور؟
</a>
@endsection