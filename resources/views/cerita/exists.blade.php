@extends('layouts.app')

@section ('content')
<div class="card">
    <div class="row">
        <div class="col">
            <div class="card-header">
                <h2 class="tmbh-data-title">Ceritamu</h2>
            </div>
            <div id="accordionPopoutIcon" class="accordion accordion-popout">
                @foreach(['pagi' => 'Waktu Pagi', 'siang' => 'Waktu Siang', 'sore' => 'Waktu Sore'] as $key => $label)
                @php
                $emosi = $data[$key]['nama_kategori_' . $key] ?? '';
                $badgeClass = '';
                $messageColor = '';
                $messageText = '';

                if ($emosi == 'Marah') {
                $badgeClass = 'text-danger';
                $messageColor = 'red';
                $messageText = 'Wah Hari ini Kamu Lagi Penuh Dengan Amarah, Ayo Redam Amarahmu Agar Hari mu Penuh Dengan Kebahagiaan';
                } elseif ($emosi == 'Sedih') {
                $badgeClass = 'text-success';
                $messageColor = 'green';
                $messageText = 'Wah Hari ini kenapa kamu bersedih, apakah ada yang mengakitimu atau membicarakan kamu yang jelek2, gpp anggap itu sebagai angin berlalu. ayo tersenyum dan berbahagialah';
                } elseif ($emosi == 'Bahagia') {
                $badgeClass = 'text-info';
                $messageColor = 'blue';
                $messageText = 'wah kamu hari ini bahagia. bagus lanjutkan bahagiamu dan jangna lupa selalu bersyukur ya';
                } else {
                $badgeClass = 'text-secondary';
                }
                @endphp
                <div class="accordion-item card {{ $loop->first ? 'active' : '' }}">
                    <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIcon{{ ucfirst($key) }}">
                        <button type="button" class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-{{ $key }}" aria-controls="accordionPopoutIcon-{{ $key }}" {{ $loop->first ? 'aria-expanded=true' : '' }}>
                            <span class="{{ $badgeClass }}">{{ $label }}</span>
                        </button>
                    </h2>
                    <div id="accordionPopoutIcon-{{ $key }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#accordionPopoutIcon">
                        <div class="accordion-body">
                            @if(!is_null($data[$key]) && !empty($data[$key]))
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="img-container">
                                        @php
                                        $gambar = $data[$key]['gambar_' . $key] ?? '';
                                        $path = Storage::url('upload/cerita/' . $key . '/' . $gambar);
                                        @endphp
                                        <img class="img-exists" src="{{ url($path) }}" alt="gambar">
                                    </div>
                                </div>

                                <div class="col-md-9 personal-info">
                                    <h3>info Cerita {{$key}}</h3>
                                    <div class="form-group">
                                        <h5 style="color: {{ $messageColor }}">{{ $messageText }}</h5>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Expresimu Hari ini:</label>
                                        <div class="col-md-8">
                                            @php
                                            $emosi = $data[$key]['nama_kategori_' . $key] ?? '';
                                            $kelasEmosi = in_array($emosi, ['Marah', 'Bahagia', 'Sedih']) ? 'text-' . $emosi : '';
                                            @endphp
                                            @if($emosi == 'Marah')
                                            <span class="badge bg-danger">Marah</span>
                                            @elseif($emosi == 'Sedih')
                                            <span class="badge bg-success">Sedih</span>
                                            @elseif($emosi == 'Bahagia')
                                            <span class="badge bg-info">Bahagia</span>
                                            @else
                                            <span class="badge bg-secondary">Tidak Diketahui</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ceritamu:</label>
                                        <div class="col-md-8">
                                            <textarea id="selected-text" name="text_cerita_{{ $key }}" oninput="autoGrow(this)" readonly class="form-control textarea-noscroll">{{ $data[$key]['text_cerita_' . $key] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Action:</label>
                                        <div class="col-md-8 d-flex align-items-center">
                                            <!-- Tombol Edit Cerita -->
                                          
                                            <a href="{{ route('cerita.edit', ['id' => $ceritaId, 'time' => $key]) }}" class="btn btn-primary me-2">Edit Cerita</a>
                                        
                                            <!-- Tombol Hidden -->
                                            <button class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-{{ $key }}" aria-expanded="true" aria-controls="accordionPopoutIcon-{{ $key }}" id="hideButton">Tutup Cerita {{$key}}</button>
                                        </div>
                                    </div>
                                    </br>
                                    </br>
                                </div>
                            </div>
                            @else
                            @if ($currentDate->isToday())
                            @if ($updateAvailable[$key])
                            
                            <p>Belum ada cerita untuk waktu {{ $label }}. <a href="{{ route('cerita.edit', ['id' => $ceritaId, 'time' => $key]) }}" class="btn btn-primary">Update Cerita {{$label}}</a></p>
                            @else
                            <p>Belum ada cerita untuk waktu {{ $label }}. Update Cerita pada waktu {{ $label }} dari {{ $waktuData[$key]->jam_mulai }} sampai {{ $waktuData[$key]->jam_selesai }}.</p>
                            @endif
                            @else
                            <p>Maaf, tanggal sudah lewat. Kamu tidak bisa mengupdate cerita kamu di waktu {{ $label }} hari.</p>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            </br>
        </div>
    </div>
</div>
<!-- <pre>{{ print_r($data[$key], true) }}</pre> -->
@endsection

@section('javascript')
<script>
    function autoGrow(element) {
        element.style.height = "auto"; /* Reset height */
        element.style.height = (element.scrollHeight) + "px"; /* Set height to scrollHeight */
    }
</script>
@endsection