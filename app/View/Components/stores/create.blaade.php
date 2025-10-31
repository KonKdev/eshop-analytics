@extends('layouts.master')


@section('content')
<div class="container">
    <h2>Σύνδεση WooCommerce Store</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('stores.store') }}">
        @csrf

        <div>
            <label for="url">Store URL</label>
            <input type="text" name="url" value="{{ old('url') }}" placeholder="https://myshop.gr" required>
            @error('url') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="consumer_key">Consumer Key</label>
            <input type="text" name="consumer_key" value="{{ old('consumer_key') }}" required>
            @error('consumer_key') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="consumer_secret">Consumer Secret</label>
            <input type="text" name="consumer_secret" value="{{ old('consumer_secret') }}" required>
            @error('consumer_secret') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Σύνδεση</button>
    </form>
</div>
@endsection
