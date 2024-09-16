@extends('layouts.app')

@section ('content')
<div class="card">
    <div class="row">
        <div class="col">
            <div class="card-header">
                <h2 class="tmbh-data-title">Bordered Table</h2>
            </div>

        </div>
        <div class="col">
            <div class="d-flex container-tmbh-data">
                @if(isset($parsedDate))
                <a class="btn btn-primary" href="{{ route('cerita.create', ['date' => $parsedDate]) }}" role="button">Tambah Cerita</a>
                @else
                <!-- Tampilkan pesan error atau link alternatif jika variabel tidak ada -->
                <p>Tanggal tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection