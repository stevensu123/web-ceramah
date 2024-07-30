@extends('layouts.app')

@section ('content')
<div class="card">
    <div class="row">
        <div class="col">
            <div class="card-header">
                <h2 class="tmbh-data-title">Ceritamu </h2>
            </div>

        </div>
        <div class="col">
        </div>
 
        <div class="accordion" id="accordionExample">
    @foreach(['pagi' => 'Waktu Pagi', 'siang' => 'Waktu Siang', 'sore' => 'Waktu Sore'] as $key => $label)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ ucfirst($key) }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($key) }}" aria-expanded="true" aria-controls="collapse{{ ucfirst($key) }}">
                    {{ $label }}
                </button>
            </h2>
            <div id="collapse{{ ucfirst($key) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ ucfirst($key) }}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @forelse($data[$key] as $cerita)
                        <div class="card mb-3">
                            <img src="{{ asset('storage/' . $cerita->gambar) }}" class="card-img-top" alt="{{ $cerita->judul }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $cerita->nama_kategori_pagi }}</h5>
                                <p class="card-text">{{ $cerita->isi }}</p>
                                <p class="card-text"><small class="text-muted">{{ $cerita->kategoris->pluck('nama')->join(', ') }}</small></p>
                            </div>
                        </div>
                    @empty
                        <p>Belum ada cerita untuk waktu {{ $label }}. <a href="{{ route('cerita.create') }}" class="btn btn-primary">Update Data Cerita</a></p>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>

    </div>
</div>
@endsection