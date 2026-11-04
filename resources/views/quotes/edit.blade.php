
@extends('Layout.app')

@section('content')
<div class="container">
    <h1>Edit Quote</h1>

    <form action="{{ route('quotes.update', $quote) }}" method="POST">
        @csrf
        @method('PUT')

        <textarea
            name="text"
            minlength="3"
            maxlength="155"
            required
        >{{ old('text', $quote->text) }}</textarea>

        @error('text')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Save Changes</button>

        <a href="{{ route('quotes.index') }}">Cancel</a>
    </form>
</div>
@endsection
