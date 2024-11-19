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
                    <div class="card-body">
                        <form action="{{route('kategori.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Nama Kategori</label>
                                <input type="text" name="nama_kategori" class="form-control" id="selected-text-name" placeholder="Default" readonly />
                            </div>
                            <!-- <div class="mb-3">
                                <label class="form-label" for="basic-default-email">Email</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        type="text"
                                        id="basic-default-email"
                                        class="form-control"
                                        placeholder="john.doe"
                                        aria-label="john.doe"
                                        aria-describedby="basic-default-email2" />
                                    <span class="input-group-text" id="basic-default-email2">@example.com</span>
                                </div>
                                <div class="form-text">You can use letters, numbers & periods</div>
                            </div> -->
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Keterangan</label>
                                <textarea id="selected-text" name="keterangan" class="form-control" readonly></textarea>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="switch" onchange="toggleSwitch()" id="flexSwitchCheckChecked" />
                                <label class="form-check-label" id="switch-label" for="switch">Inactive</label>


                            </div>
                            <div class="mb-3">
                                <label class="form-label" class="form-label">Pilih Kategori</label>
                                <select id="image-select" name="gambar" onchange="showImageAndText()" class="form-select">
                                    <option value="">Default select</option>
                                    <option value="marah">Marah</option>
                                    <option value="sedih">Sedih</option>
                                    <option value="bahagia">Bahagia</option>
                                </select>
                            </div>
                            <div id="image-container" class="mt-3" style="display: none;">
                                <!-- Tempat untuk menampilkan gambar -->
                                <img id="selected-image" name="image" class="select-img" src="" alt="selected">

                            </div>
                            <div id="file-input-container" style="display: none;">
                                <label for="image">Upload gambar:</label>
                                <input type="file" name="image" id="image">
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

@push('javascript')
<script>
    // function showImage() {
    //     var selectBox = document.getElementById("image-select");
    //     var selectedValue = selectBox.value;
    //     var imageContainer = document.getElementById("image-container");
    //     var image = document.getElementById("selected-image");

    //     // Periksa jika nilai yang dipilih bukan default
    //     if (selectedValue !== "") {
    //         image.src = "{{asset ('assets/dashboard/img/emotikon')}}/" + selectedValue + ".png"; // Tampilkan gambar sesuai dengan nilai yang dipilih
    //         imageContainer.style.display = "block"; // Tampilkan kontainer gambar
    //     } else {
    //         image.src = ""; // Kosongkan src gambar
    //         imageContainer.style.display = "none"; // Sembunyikan kontainer gambar
    //     }
    // }
    function showImageAndText() {
        var selectBox = document.getElementById("image-select");
        var selectedValue = selectBox.value;
        var imageContainer = document.getElementById("image-container");
        var image = document.getElementById("selected-image");
        var textArea = document.getElementById("selected-text");
        var input_name = document.getElementById("selected-text-name");
        const fileInput = document.getElementById('image');


        // Periksa jika nilai yang dipilih bukan default
        if (selectedValue !== "") {

            image.src = "{{asset ('assets/dashboard/img/emotikon')}}/" + selectedValue + ".png"; // Tampilkan gambar sesuai dengan nilai yang dipilih
            imageContainer.style.display = "block"; // Tampilkan kontainer gambar

            // Tampilkan teks yang sesuai dengan nilai yang dipilih
            switch (selectedValue) {
                case "marah":
                    textArea.value = "Emotikon Marah Menandakan Suasana Hatimu Lagi Penuh Dengan Kemarahan";
                    input_name.value = "Marah";
                    break;
                case "sedih":
                    textArea.value = "Emotikon Sedih Menandakan Suasana Hatimu Lagi Penuh Dengan Kesedihan";
                    input_name.value = "Sedih";
                    break;
                case "bahagia":
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

    function toggleSwitch() {
        var switchElement = document.getElementById("switch");
        var switchLabel = document.getElementById("switch-label");

        if (switchElement.checked) {
            switchLabel.innerHTML = "Active";
            switchLabel.classList.remove("text-danger"); // Optional: Remove danger class
            switchLabel.classList.add("text-success"); // Optional: Add success class
        } else {
            switchLabel.innerHTML = "Inactive";
            switchLabel.classList.remove("text-success"); // Optional: Remove success class
            switchLabel.classList.add("text-danger"); // Optional: Add danger class
        }
    }
</script>
@endpush