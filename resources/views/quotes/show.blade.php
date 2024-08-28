
        @extends('layouts.app')

@section('content')
<div class="container">
<h1>Translate Quote</h1>
<h1>Kutipan</h1>
    <p><strong>Text:</strong> {{ $quote->text }}</p>
    <p><strong>Terjemahan:</strong> {{ $quote->translated_text }}</p>
    <p><strong>Kategori:</strong> 
        @foreach($quote->categories as $category)
            {{ $category->nama_kategori }}@if(!$loop->last), @endif
        @endforeach
    </p>
</div>

@endsection

