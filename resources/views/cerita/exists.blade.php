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
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-3">
                                <div class="img-container">
                                    @php $path = Storage::url('upload/cerita/' . $key . '/' . ($key === 'pagi' ? $cerita->gambar_pagi : ($key === 'siang' ? $cerita->gambar_siang : $cerita->gambar_sore))); @endphp
                                    <img class="img-exists" src="{{url($path)}}" class="avatar img-circle" alt="avatar">
                                </div>
                            </div>
                            <!-- edit form column -->
                            <div class="col-md-9 personal-info">
                                <h3>Personal info</h3>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Expresimu Hari ini:</label>
                                    <div class="col-md-8">
                                        @php
                                        $emosi = $cerita->getNamaKategoriByWaktu($key);
                                        $validEmosi = ['Marah', 'Bahagia', 'Sedih'];
                                        $kelasEmosi = in_array($emosi, $validEmosi) ? 'text-' . $emosi : '';
                                        @endphp
                                        <h6 class="{{ $kelasEmosi }}">
                                            {{ htmlspecialchars($cerita->getNamaKategoriByWaktu($key), ENT_QUOTES, 'UTF-8') }}
                                        </h6>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                        <label class="col-lg-3 control-label">Email:</label>
                                        <div class="col-lg-8">
                                            <p class="card-text"><small class="text-muted">{{ $cerita->kategoris->pluck('nama_kategori')->join(',') }}</small></p>
                                            <p class="card-text"><small class="text-muted">{{ $cerita->kategoris->pluck('nama_kategori')->join(',') }}</small></p>
                                        </div>
                                    </div> -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Ceritamu:</label>
                                    <div class="col-md-8">
                                        <textarea id="selected-text" name="text_cerita_pagi" oninput="autoGrow(this)" readonly class="form-control textarea-noscroll">{{$cerita->getNamaTextCeritaByWaktu($key)}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Ceritamu:</label>
                                    <div class="col-md-8">
                                    <a href="{{ route('cerita.edit', [$cerita->id, $key]) }}" class="btn btn-primary">Tambah Data Cerita</a>
                                </div>
                            </div>
                        </div>
                        @empty
                        @php
                        // Mengambil tanggal cerita dari data yang dikirim ke view
                        $ceritaDate = \Carbon\Carbon::parse($currentDate);
                        @endphp

                        @if ($ceritaDate->isToday())
                        <!-- Jika tanggal cerita adalah hari ini -->
                        <p>Belum ada cerita untuk waktu {{ $label }}.
                        <a href="{{ route('cerita.edit', ['id' => 0, 'time' => $key]) }}" class="btn btn-primary">Tambah Data Cerita</a>
                        </p>
                        @else
                        <!-- Jika tanggal cerita sudah lewat -->
                        <p>Maaf, tanggal sudah lewat. Kamu tidak bisa mengupdate cerita kamu di waktu {{ $label }} hari.</p>
                        @endif
                        <!-- <p>Belum ada cerita untuk waktu {{ $label }}. <a href="{{ route('cerita.edit', $cerita->id) }}" class="btn btn-primary">Update Data Cerita</a></p> -->
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach
        </div>



    </div>
</div>
@endsection

@section('javascript')
<script>
    function autoGrow(element) {
        element.style.height = "auto"; /* Reset height */
        element.style.height = (element.scrollHeight) + "px"; /* Set height to scrollHeight */
    }
</script>
@endsection