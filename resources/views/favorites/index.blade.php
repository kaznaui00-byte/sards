@extends('Layout.app')

@section('content')
<div class="container">
    <h1>My Favorites ⭐</h1>

    <p>
        <a href="{{ route('quotes.index') }}">Back to Quotes</a>
    </p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @forelse ($favorites as $favorite)
        <article class="my-4 rounded-lg border p-4">
            <p>{{ $favorite->quote->text }}</p>

            <p>
                <small>
                    By {{ $favorite->quote->user->name ?? 'Unknown' }}
                </small>
            </p>

            <form action="{{ route('quotes.favorite', $favorite->quote) }}" method="POST">
                @csrf
                <button type="submit">Remove from Favorites ⭐</button>
            </form>
        </article>
    @empty
        <p>You haven't saved any favorites yet.</p>
    @endforelse
</div>
@endsection
