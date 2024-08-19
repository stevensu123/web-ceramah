@extends('layouts.app')

@section ('content')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Vertical Layouts</h4>
        <!-- Basic Layout -->
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
                    <!-- <div class="card-header d-flex justify-content-end align-items-end">
                    <label id="hari-ini"></label> <label name="tanggal" id="tanggal-hari-ini"></label>
                    </div> -->
                    <div class="card-body">
                        <form action="{{route('cerita.store')}}" method="post" enctype="multipart/form-data">
                            @csrf



                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div id="image-container1" class="mt-3">
                                            <!-- Tempat untuk menampilkan gambar -->
                                            @if ($gambar)
                                            @php
                                            // Menentukan nama atribut gambar berdasarkan waktu
                                            $gambarField = ($time === 'pagi') ? 'gambar_pagi' : (($time === 'siang') ? 'gambar_siang' : 'gambar_sore');
                                            $path = Storage::url('upload/cerita/' . $time . '/' . $cerita->$gambarField);
                                            @endphp
                                            <img id="img1" class="select-img" src="{{url($path)}}" alt="selected">
                                            @endif
                                           

                                        </div>
                                        <div id="file-input-container" style="display: none;">
                                            <label for="image">Upload gambar:</label>
                                            <input type="file" name="file1" id="file1">
                                            @error('file1')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- edit form column -->
                                <div class="col-md-9 personal-info">
                                    <h3>Edit Cerita info</h3>

                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Username:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" id="selected-text-name1" value="{{$cerita->nama_kategori_pagi}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Password:</label>
                                            <div class="col-md-8">
                                                <label class="form-label" class="form-label">Pilih Kategori</label>
                                                <select id="select1" name="gambar" onchange="showImageAndText(1)" class="form-select">
                                                    <option value="">Default select</option>
                                                    @foreach($kategoriList as $kat)
                                                    <option value="{{ $kat->nama_kategori }}" {{ $kat->nama_kategori === $kategori ? 'selected' : '' }}>
                                                        {{ $kat->nama_kategori }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="form-label" for="basic-default-message">Keterangan</label>
                                            <div class="col-md-8">
                                                <h6 style="color: blue; font-size: 10px;" for="">Deskripsi dari Emotikon yang dipilih</h6>
                                                <textarea id="selected-text1" name="keterangan_pagi"  class="form-control" readonly>{{ $keterangan }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="form-label" for="basic-default-message">Keterangan</label>
                                            <div class="col-md-8">
                                                <h6 style="color: blue; font-size: 10px;" for="">Deskripsi dari Emotikon yang dipilih</h6>
                                                <textarea id="selected-text1" name="keterangan_pagi"  class="form-control"~>{{ $textCerita }}</textarea>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-md-3 control-label"></label>
                                            <div class="col-md-8">
                                                <input type="button" class="btn btn-primary" value="Save Changes">
                                                <span></span>
                                                <input type="reset" class="btn btn-default" value="Cancel">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
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