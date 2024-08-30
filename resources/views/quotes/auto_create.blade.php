<!-- resources/views/quotes/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
<form action="{{ route('quotes.autoStore') }}" method="POST">
        @csrf
        <label for="category">Category:</label>
        <select name="category_id" id="category" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
        <br><br>

        <label for="quote_length">Quote Length:</label>
        <select name="quote_length" id="quote_length" required>
            <option value="short">Short</option>
            <option value="medium">Medium</option>
            <option value="long">Long</option>
        </select>
        <br><br>

        <button type="submit" name="auto" value="1">Auto Generate</button>
    </form>
</div>
@endsection

@section ('javascript')

