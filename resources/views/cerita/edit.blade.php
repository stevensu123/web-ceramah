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
                        <div class="accordion-item card " id="headingAfternoon">
                                    <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconTwo">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-2" aria-controls="accordionPopoutIcon-2">
                                            Ceritamu Di {{ $waktu[1]->title}} Hari ini
                                        </button>
                                    </h2>
                                    <div id="accordionPopoutIcon-2" class="accordion-collapse collapse" data-bs-parent="#accordionPopoutIcon">
                                        <div class="accordion-body">
                                            <input type="hiddne" name="waktu_siang" value="{{$waktu[1]->title}}">
                                            <div class="mb-3">
                                                <label class="form-label" class="form-label">Pilih Kategori</label>
                                                <select id="select2" name="gambar" onchange="showImageAndText(2)" class="form-select">
                                                    <option value="">Default select</option>
                                                    @foreach ($kategori as $kt)
                                                    <option value="{{$kt->nama_kategori}}">{{$kt->nama_kategori}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="image-container2" class="mt-3" style="display: none;">
                                                <!-- Tempat untuk menampilkan gambar -->
                                                <img id="img2" class="select-img" src="" alt="selected">

                                            </div>
                                            <div id="file-input-container " style="display: block;">
                                                <label for="image">Upload gambar:</label>
                                                <input type="file" name="file2" id="file2">
                                                @error('file2')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-fullname">Nama Kategori</label>
                                                <input type="text" name="nama_kategori_siang" class="form-control" id="selected-text-name2" placeholder="Default" readonly />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Keterangan</label>
                                                <h6 style="color: blue; font-size: 10px;" for="">Deskripsi dari Emotikon yang dipilih</h6>
                                                <textarea id="selected-text2" name="keterangan_siang" class="form-control" readonly></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Cerita Kamu Hari ini</label>
                                                <h6 style="color: blue; font-size: 10px;  overflow: hidden;  resize: none;" for="">Tempat Memasukan Curhatan isi Hati Kamu</h6>
                                                <textarea id="selected-text" name="text_cerita_siang" oninput="autoGrow(this)" class="form-control textarea-noscroll"></textarea>
                                            </div>
                                        </div>
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