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

        <div id="accordionPopoutIcon" class="accordion accordion-popout">
            <div class="accordion-item card">
                <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-1" aria-controls="accordionPopoutIcon-1">
                        Pagi Hari ini
                    </button>
                </h2>
                <div id="accordionPopoutIcon-1" class="accordion-collapse collapse show" data-bs-parent="#accordionPopoutIcon">
                    <div class="accordion-body">
                        <div class="container">
                            <h1>{{$cerita->nama_kategori_pagi}}</h1>
                            <hr>
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php $path = Storage::url('upload/cerita/pagi/'.$cerita->gambar_pagi);@endphp
                                        <img src="{{url($path)}}" class="avatar img-circle" alt="avatar">
                                    </div>
                                </div>
                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    <h3>Cerita info</h3>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Suasana Hatimu:</label>
                                        <div class="col-md-8">
                                            @php
                                            $class = '';
                                            switch ($cerita->nama_kategori_pagi) {
                                            case 'Marah':
                                            $class = 'text-marah';
                                            break;
                                            case 'Sedih':
                                            $class = 'text-sedih';
                                            break;
                                            case 'Bahagia':
                                            $class = 'text-bahagia';
                                            break;
                                            }
                                            @endphp
                                            <h4 class="{{$class}}">{{$cerita->nama_kategori_pagi}}</h4>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Keterangan:</label>
                                        <div class="col-lg-8">
                                            <p style="font-weight: bold;">{{$cerita->keterangan_pagi}}</p>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ceritamu:</label>
                                        <div class="col-md-8">
                                            <p class="text_cerita">{{$cerita->text_cerita_pagi}}</p>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="accordion-item card">
                <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconTwo">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-2" aria-controls="accordionPopoutIcon-2">
                        Siang Hari ini
                    </button>
                </h2>
                <div id="accordionPopoutIcon-2" class="accordion-collapse collapse " data-bs-parent="#accordionPopoutIcon">
                    <div class="accordion-body">
                        <div class="container">
                            <h1>{{$cerita->nama_kategori_siang}}</h1>
                            <hr>
                            @if(empty($data))
                            <h6>Maaf  Kamu Belom Ada Cerita</h6>
                            <span>Ayo Update Data Kamu</span>
                            <a class="btn btn-primary" href="{{ route('cerita.edit', $cerita->id) }}" role="button">Update Cerita</a>
                            @else
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php $path = Storage::url('upload/cerita/pagi/'.$cerita->gambar_pagi);@endphp
                                        <img src="{{url($path)}}" class="avatar img-circle" alt="avatar">
                                    </div>
                                </div>
                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    <h3>Cerita info</h3>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Suasana Hatimu:</label>
                                        <div class="col-md-8">
                                            @php
                                            $class = '';
                                            switch ($cerita->nama_kategori_siang) {
                                            case 'Marah':
                                            $class = 'text-marah';
                                            break;
                                            case 'Sedih':
                                            $class = 'text-sedih';
                                            break;
                                            case 'Bahagia':
                                            $class = 'text-bahagia';
                                            break;
                                            }
                                            @endphp
                                            <h4 class="{{$class}}">{{$cerita->nama_kategori_siang}}</h4>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Keterangan:</label>
                                        <div class="col-lg-8">
                                            <p style="font-weight: bold;">{{$cerita->keterangan_pagi}}</p>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ceritamu:</label>
                                        <div class="col-md-8">
                                            <p class="text_cerita">{{$cerita->text_cerita_pagi}}</p>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="accordion-item card">
                <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconThree">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-3" aria-controls="accordionPopoutIcon-3">
                        Sore Hari ini
                    </button>
                </h2>
                <div id="accordionPopoutIcon-3" class="accordion-collapse collapse" data-bs-parent="#accordionPopoutIcon">
                    <div class="accordion-body">
                        <div class="container">
                            <h1>{{$cerita->nama_kategori_pagi}}</h1>
                            <hr>
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @php $path = Storage::url('upload/cerita/pagi/'.$cerita->gambar_pagi);@endphp
                                        <img src="{{url($path)}}" class="avatar img-circle" alt="avatar">
                                    </div>
                                </div>
                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    <h3>Cerita info</h3>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Suasana Hatimu:</label>
                                        <div class="col-md-8">
                                            @php
                                            $class = '';
                                            switch ($cerita->nama_kategori_pagi) {
                                            case 'Marah':
                                            $class = 'text-marah';
                                            break;
                                            case 'Sedih':
                                            $class = 'text-sedih';
                                            break;
                                            case 'Bahagia':
                                            $class = 'text-bahagia';
                                            break;
                                            }
                                            @endphp
                                            <h4 class="{{$class}}">{{$cerita->nama_kategori_pagi}}</h4>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Keterangan:</label>
                                        <div class="col-lg-8">
                                            <p style="font-weight: bold;">{{$cerita->keterangan_pagi}}</p>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ceritamu:</label>
                                        <div class="col-md-8">
                                            <p class="text_cerita">{{$cerita->text_cerita_pagi}}</p>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection