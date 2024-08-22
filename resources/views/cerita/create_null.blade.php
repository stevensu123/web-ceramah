@extends('layouts.app')

@section ('content')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Vertical Layouts</h4>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Basic Layout</h5>
                        <small class="text-muted float-end">Default label</small>
                    </div>
                    <div class="deskripsi-label">
                        <label for="">
                            Ayo Ceritakan Suasana Hatimu hari ini
                        </label>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card-body">
                        @if (in_array($time, ['pagi', 'siang', 'sore']))
                        <h1>Ini adalah waktu {{ $time }}</h1>
                        <form action="{{ route('cerita.update', $cerita->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tanggal" value="{{$currentDate}}">
                            <input type="hidden" name="waktu_{{$time}}" value="{{$time}}">

                            <div class="row">
                                <!-- Left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @for ($i = 1; $i <= 3; $i++)
                                            @if (($time==='pagi' && $i===1) || ($time==='siang' && $i===2) || ($time==='sore' && $i===3))
                                            <div id="image-container{{ $i }}" class="mt-3">
                                           
                                            <img id="img{{ $i }}" class="select-img" src="" alt="selected">
                                          
                                    </div>
                                    <div id="file-input-container{{ $i }}" style="display: block;">
                                        <label for="image{{ $i }}">Upload gambar {{ $i }}:</label>
                                        <input type="file" name="file{{ $i }}" id="file{{ $i }}">
                                        @error('file' . $i)
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @else
                                    <div id="image-container{{ $i }}" style="display: none;"></div>
                                    <div id="file-input-container{{ $i }}" style="display: none;"></div>
                                    @endif
                                    @endfor
                                </div>
                            </div>
                            <!-- Edit form column -->
                            <div class="col-md-9 personal-info">
                                <h3>Edit Cerita Info</h3>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Suasana Hatimu Hari ini:</label>
                                    <div class="col-md-8">
                                        @for ($i = 1; $i <= 3; $i++)
                                            @if (($time==='pagi' && $i===1) || ($time==='siang' && $i===2) || ($time==='sore' && $i===3))
                                            <input class="form-control" type="text" name="nama_kategori_{{ $time }}" id="selected-text-name{{ $i }}">
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Pilih Kategori</label>
                                    <div class="col-md-8">
                                        @for ($i = 1; $i <= 3; $i++)
                                            @if (($time==='pagi' && $i===1) || ($time==='siang' && $i===2) || ($time==='sore' && $i===3))
                                            <select id="select{{ $i }}" name="gambar{{ $i }}" onchange="showImageAndText({{ $i }})" class="form-select">
                                            <option value="">Default select</option>
                                            @foreach($kategoriList as $kat)
                                            <option value="{{ $kat->nama_kategori }}" {{ $kat->nama_kategori === $kategori ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                            @endforeach
                                            </select>
                                            @error('gambar' . $i)
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            @endif
                                            @endfor
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-message">Keterangan</label>
                                    <div class="col-md-8">
                                        <h6 style="color: blue; font-size: 10px;">Deskripsi dari Emotikon yang dipilih</h6>
                                        @for ($i = 1; $i <= 3; $i++)
                                            @if (($time==='pagi' && $i===1) || ($time==='siang' && $i===2) || ($time==='sore' && $i===3))
                                            <textarea id="selected-text{{ $i }}" name="keterangan_{{ $time }}" class="form-control" readonly></textarea>
                                            @endif
                                            @endfor
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-message">Cerita Anda</label>
                                    <div class="col-md-8">
                                        <h6 style="color: blue; font-size: 10px;">Ayo Curahkan Isi Hati Kamu Disini:</h6>
                                        <textarea id="selected-text{{ $i }}" name="text_cerita_{{ $time }}" class="form-control">
                                   
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" class="btn btn-primary" value="Save Changes">
                                        <input type="reset" class="btn btn-default" value="Cancel">
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                    @else
                    <h1>Waktu tidak dikenali</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('javascript')
<script>
    function showImageAndText(selectNumber) {
        var selectBox = document.getElementById(`select${selectNumber}`);
        var imageContainer = document.getElementById(`image-container${selectNumber}`);
        var image = document.getElementById(`img${selectNumber}`);
        var textArea = document.getElementById(`selected-text${selectNumber}`);
        var input_name = document.getElementById(`selected-text-name${selectNumber}`);
        const fileInput = document.getElementById(`file${selectNumber}`);
        var selectedValue = selectBox.value;
        // Periksa jika nilai yang dipilih bukan default
        if (selectedValue !== "") {
            image.src = "{{asset ('assets/dashboard/img/emotikon')}}/" + selectedValue + ".png"; // Tampilkan gambar sesuai dengan nilai yang dipilih
            imageContainer.style.display = "block"; // Tampilkan kontainer gambar
            // Tampilkan teks yang sesuai dengan nilai yang dipilih
            switch (selectedValue) {
                case "Marah":
                    textArea.value = "Emotikon Marah Menandakan Suasana Hatimu Lagi Penuh Dengan Kemarahan";
                    input_name.value = "Marah";
                    break;
                case "Sedih":
                    textArea.value = "Emotikon Sedih Menandakan Suasana Hatimu Lagi Penuh Dengan Kesedihan";
                    input_name.value = "Sedih";
                    break;
                case "Bahagia":
                    textArea.value = "Emotikon Bahagia Menandakan Suasana Hatimu Lagi Penuh Dengan KeBahagiaan";
                    input_name.value = "Bahagia";
                    break;
                default:
                    textArea.value = "";
                    input_name.value = "";
                    break;
            }
            fetch(image.src)
                .then(response => response.blob())
                .then(blob => {
                    const file = new File([blob], `${selectedValue}.png`, {
                        type: blob.type
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                });
            textArea.style.display = "block";
            input_name.style.display = "block";
        } else {
            image.src = ""; // Kosongkan src gambar
            imageContainer.style.display = "none"; // Sembunyikan kontainer gambar
            textArea.value = ""; // Kosongkan teks
            textArea.style.display = ""; // Sembunyikan area teks
            input_name.value = ""; // Kosongkan teks
            input_name.style.display = ""; // Sembunyikan area teks
        }
    }

    function autoGrow(element) {
        element.style.height = "auto"; /* Reset height */
        element.style.height = (element.scrollHeight) + "px"; /* Set height to scrollHeight */
    }
</script>


@endsection