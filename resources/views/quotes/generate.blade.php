<!-- resources/views/quotes/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Quote</h2>
    <form action="{{ route('quotes.generate') }}" method="POST">
        @csrf
        <label for="category">Select Category:</label>
        <select id="category" name="category">
            @foreach($categories as $category)
                <option value="{{ $category->nama_kategori }}">{{ $category->nama_kategori }}</option>
            @endforeach
        </select>
        <button type="submit">Generate Quote</button>
    </form>
</div>
@endsection

@section ('javascript')

