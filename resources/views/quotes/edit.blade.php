@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Quote</h1>
    <form action="{{ route('quotes.update', $quote->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="emotion">Emotion</label>
            <select name="emotion" id="emotion" class="form-control">
                <option value="angry" {{ $quote->emotion == 'angry' ? 'selected' : '' }}>Marah</option>
                <option value="sad" {{ $quote->emotion == 'sad' ? 'selected' : '' }}>Sedih</option>
                <option value="happy" {{ $quote->emotion == 'happy' ? 'selected' : '' }}>Bahagia</option>
            </select>
        </div>
        <div class="form-group">
            <label for="length">Length</label>
            <select name="length" id="length" class="form-control">
                <option value="short" {{ $quote->length == 20 ? 'selected' : '' }}>Pendek (20 kata)</option>
                <option value="medium" {{ $quote->length == 40 ? 'selected' : '' }}>Sedang (40 kata)</option>
                <option value="long" {{ $quote->length == 70 ? 'selected' : '' }}>Panjang (70 kata)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="categories">Categories</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, $quote->categories->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Quote</button>
    </form>
</div>
@endsection
