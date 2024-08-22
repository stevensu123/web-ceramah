@extends('layouts.app')

@section ('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Cerita</h4>

        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create Cerita</h5>
                        <small class="text-muted float-end">Cerita </small>
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
                            <div id="accordionPopoutIcon" class="accordion accordion-popout">

                                <div class="accordion-item card active" id="headingMorning">
                                    <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconOne">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-1" aria-controls="accordionPopoutIcon-1">
                                            Ceritamu Di {{ $waktu[0]->title }} Hari ini Jam {{ $waktu[0]->jam_mulai }} - {{ $waktu[0]->jam_selesai }}
                                        </button>
                                    </h2>

                                    <div id="accordionPopoutIcon-1" class="accordion-collapse collapse" data-bs-parent="#accordionPopoutIcon">
                                        <div class="accordion-body">
                                            <input type="hidden" id="input_pagi" required name="waktu_pagi" value="" required>

                                            <div class="mb-3">
                                                <label class="form-label">Pilih Kategori</label>
                                                <select id="select1" name="nama_kategori_pagi" onchange="showImageAndText(1)"
                                                    class="form-select ">
                                                    <option value="">Default select</option>
                                                    @foreach ($kategori as $kt)
                                                    <option value="{{ $kt->nama_kategori }}">{{ $kt->nama_kategori }}</option>
                                                    @endforeach
                                                </select>
                                                @error('nama_kategori_pagi')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div id="image-container1" class="mt-3" style="display: none;">
                                                <!-- Tempat untuk menampilkan gambar -->
                                                <img id="img1" class="select-img" src="" alt="selected">
                                            </div>

                                            <div id="file-input-container" style="display: none;">
                                                <label for="file1">Upload gambar:</label>
                                                <input type="file" name="file1" id="file1">
                                                @error('file1')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-fullname">Nama Kategori</label>
                                                <input type="text" name="nama_kategori_pagi" class="form-control" id="selected-text-name1" placeholder="Default" readonly />
                                                @error('nama_kategori_pagi')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Keterangan</label>
                                                <h6 style="color: blue; font-size: 10px;" for="">Deskripsi dari Emotikon yang dipilih</h6>
                                                <textarea id="selected-text1" name="keterangan_pagi" class="form-control" readonly></textarea>
                                                @error('keterangan_pagi')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Cerita Kamu Hari ini</label>
                                                <h6 style="color: blue; font-size: 10px; overflow: hidden; resize: none;" for="">Tempat Memasukan Curhatan isi Hati Kamu</h6>
                                                <textarea id="selected-text" name="text_cerita_pagi" oninput="autoGrow(this)" class="form-control textarea-noscroll"></textarea>
                                                @error('text_cerita_pagi')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item card " id="headingAfternoon">
                                    <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconTwo">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-2" aria-controls="accordionPopoutIcon-2">
                                            Ceritamu Di {{ $waktu[1]->title}} Hari ini Jam {{$waktu[1]->jam_mulai}} - {{$waktu[1]->jam_selesai}}
                                        </button>
                                    </h2>
                                    <div id="accordionPopoutIcon-2" class="accordion-collapse collapse" data-bs-parent="#accordionPopoutIcon">
                                        <div class="accordion-body">
                                            <input type="hidden" id="input_siang" name="waktu_siang" value="" required>
                                            <div class="mb-3">
                                                <label class="form-label" class="form-label">Pilih Kategori</label>
                                                <select id="select2" name="nama_kategori_siang" onchange="showImageAndText(2)" class="form-select">
                                                    <option value="">Default select</option>
                                                    @foreach ($kategori as $kt)
                                                    <option value="{{$kt->nama_kategori}}">{{$kt->nama_kategori}}</option>
                                                    @endforeach
                                                </select>
                                                @error('nama_kategori_siang')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div id="image-container2" class="mt-3" style="display: none;">
                                                <!-- Tempat untuk menampilkan gambar -->
                                                <img id="img2" class="select-img" src="" alt="selected">

                                            </div>
                                            <div id="file-input-container " style="display: none;">
                                                <label for="image">Upload gambar:</label>
                                                <input type="file" name="file2" id="file2">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-fullname">Nama Kategori</label>
                                                <input type="text" name="nama_kategori_siang" class="form-control" id="selected-text-name2" placeholder="Default" readonly />
                                                @error('nama_kategori_siang')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Keterangan</label>
                                                <h6 style="color: blue; font-size: 10px;" for="">Deskripsi dari Emotikon yang dipilih</h6>
                                                <textarea id="selected-text2" name="keterangan_siang" class="form-control" readonly></textarea>
                                                @error('keterangan_siang')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Cerita Kamu Hari ini</label>
                                                <h6 style="color: blue; font-size: 10px;  overflow: hidden;  resize: none;" for="">Tempat Memasukan Curhatan isi Hati Kamu</h6>
                                                <textarea id="selected-text" name="text_cerita_siang" oninput="autoGrow(this)" class="form-control textarea-noscroll"></textarea>
                                                @error('text_cerita_siang')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item card " id="headingEvening">
                                    <h2 class="accordion-header text-body d-flex justify-content-between" id="accordionPopoutIconThree">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionPopoutIcon-3" aria-expanded="true" aria-controls="accordionPopoutIcon-3" role="tabpanel">
                                            Ceritamu Di {{ $waktu[2]->title}} Hari ini Jam {{$waktu[2]->jam_mulai}} - {{$waktu[2]->jam_selesai}}
                                        </button>
                                    </h2>
                                    <div id="accordionPopoutIcon-3" class="accordion-collapse collapse" data-bs-parent="#accordionPopoutIcon">
                                        <div class="accordion-body">
                                            <input type="hiddne" id="input_sore" name="waktu_sore" value="">
                                            <div class="mb-3">
                                                <label class="form-label" class="form-label">Pilih Kategori</label>
                                                <select id="select3" name="nama_kategori_sore" onchange="showImageAndText(3)" class="form-select">
                                                    <option value="">Default select</option>
                                                    @foreach ($kategori as $kt)
                                                    <option value="{{$kt->nama_kategori}}">{{$kt->nama_kategori}}</option>
                                                    @endforeach
                                                </select>
                                                @error('nama_kategori_sore')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div id="image-container3" class="mt-3" style="display: none;">
                                                <!-- Tempat untuk menampilkan gambar -->
                                                <img id="img3" class="select-img" src="" alt="selected">

                                            </div>
                                            <div id="file-input-container" style="display: block;">
                                                <label for="image">Upload gambar:</label>
                                                <input type="file" name="file3" id="file3">
                                                @error('file3')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-fullname">Nama Kategori</label>
                                                <input type="text" name="nama_kategori_sore" class="form-control" id="selected-text-name3" placeholder="Default" readonly />
                                                @error('nama_kategori_sore')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Keterangan</label>
                                                <h6 style="color: blue; font-size: 10px;" for="">Deskripsi dari Emotikon yang dipilih</h6>
                                                <textarea id="selected-text3" name="keterangan_sore" class="form-control" readonly></textarea>
                                                @error('keterangan_sore')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-default-message">Cerita Kamu Hari ini</label>
                                                <h6 style="color: blue; font-size: 10px;  overflow: hidden;  resize: none;" for="">Tempat Memasukan Curhatan isi Hati Kamu</h6>
                                                <textarea id="selected-text" name="text_cerita_sore" oninput="autoGrow(this)" class="form-control textarea-noscroll"></textarea>
                                                @error('text_cerita_sore')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <label class="form-label" for="basic-default-message">Status</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" checked name="status" value="1" id="switch" />
                                <label class="form-check-label" id="switch-label" for="switch">Active</label>
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
<!--- javascript select gambar ke input ---->
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
<!--- end javascript select gambar ke input ---->

<!--- javascript mendapatkan tanggal hari ini ---->
<script>
    // Mendapatkan tanggal hari ini
    //     const today = new Date();
    //     // Mendapatkan hari dalam seminggu (0-6) dimana 0 adalah Minggu
    //     const dayOfWeek = today.getDay();
    //     // Array nama hari dalam seminggu
    //     const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    //     // Mendapatkan nama hari
    //     const dayName = days[dayOfWeek];
    //     // Mendapatkan tanggal
    //     const day = today.getDate();
    //     // Mendapatkan bulan (dimulai dari 0, jadi perlu ditambah 1)
    //     const month = today.getMonth() + 1;
    //     // Mendapatkan tahun
    //     const year = today.getFullYear();
    //     // Format tanggal lengkap
    //     const formattedDate = ` ${day}-${month}-${year}`;
    //     const formattedHari = ` ${dayName},`;
    //     // Menampilkan tanggal di dashboard
    //     document.getElementById('tanggal-hari-ini').innerText = formattedDate;
    //     document.getElementById('hari-ini').innerText = formattedHari;
</script>
<!--- end javascript mendapatkan tanggal hari ini ---->

<!--- javascript mengatur status active/inactive ---->
<script>
    function updateLabel() {
        var switchElement = document.getElementById("switch");
        var switchLabel = document.getElementById("switch-label");

        // Update value based on checkbox status
        switchElement.value = switchElement.checked ? "1" : "0";

        // Update label text and class
        switchLabel.innerHTML = switchElement.checked ? "Active" : "Inactive";
        switchLabel.classList.toggle("text-success", switchElement.checked);
        switchLabel.classList.toggle("text-danger", !switchElement.checked);
    }

    document.getElementById("switch").addEventListener("change", updateLabel);

    // Set initial label and value based on the initial state of the checkbox
    document.addEventListener("DOMContentLoaded", updateLabel);
</script>
<!--- end javascript status active/inactive --->

<!--- javascript mengatur waktu ---->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const now = new Date();
        const hour = now.getHours();
        // Define time ranges
        const morningStart = parseInt("{{$waktu[0]->jam_mulai}}", 10);
        const morningEnd = parseInt("{{$waktu[0]->jam_selesai}}", 10);
        const afternoonStart = parseInt("{{$waktu[1]->jam_mulai}}", 10);
        const afternoonEnd = parseInt("{{$waktu[1]->jam_selesai}}", 10);
        const eveningStart = parseInt("{{$waktu[2]->jam_mulai}}", 10);
        const eveningEnd = parseInt("{{$waktu[2]->jam_selesai}}", 10);

        const input_pagi = document.getElementById('input_pagi');
        const input_siang = document.getElementById('input_siang');
        const input_sore = document.getElementById('input_sore');
        // Set input values based on time
        if (hour >= morningStart && hour < morningEnd) {
            input_pagi.value = "{{$waktu[0]->title}}";
        } else {
            input_pagi.value = '';
        }
        if (hour >= afternoonStart && hour < afternoonEnd) {
            input_siang.value = "{{$waktu[1]->title}}";
        } else {
            input_siang.value = '';
        }
        if (hour >= eveningStart && hour < eveningEnd) {
            input_sore.value = "{{$waktu[2]->title}}";
        } else {
            input_sore.value = '';
        }
        if (hour >= morningEnd) {
            input_pagi.value = "{{$waktu[0]->title}}";
        }
        if (hour >= afternoonEnd) {
            input_siang.value = "{{$waktu[1]->title}}";
        }
        if (hour >= eveningEnd) {
            input_sore.value = "{{$waktu[2]->title}}";
        }
        console.log("Pagi Value After:", input_pagi.value);
        console.log("Siang Value After:", input_siang.value);
        console.log("Sore Value After:", input_sore.value);
        // Hide all accordion items initially
        const accordionItems = document.querySelectorAll('.accordion-item');
        accordionItems.forEach(item => item.style.display = 'none');
        // Show accordion items based on current time
        if (hour >= morningStart && hour < morningEnd) {
            document.getElementById('headingMorning').style.display = 'block';
        }
        if (hour >= afternoonStart && hour < afternoonEnd) {
            document.getElementById('headingAfternoon').style.display = 'block';
        }
        if (hour >= eveningStart && hour < eveningEnd) {
            document.getElementById('headingEvening').style.display = 'block';
        }

        // Always display past time headings
        if (hour >= morningEnd) {
            document.getElementById('headingMorning').style.display = 'block';
        }
        if (hour >= afternoonEnd) {
            document.getElementById('headingAfternoon').style.display = 'block';
        }
        if (hour >= eveningEnd) {
            document.getElementById('headingEvening').style.display = 'block';
        }
    });
</script>
<!--- end javascript waktu --->
@endsection