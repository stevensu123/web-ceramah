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
                                                <!-- <img src="{{ asset('storage/' . $cerita->gambar) }}" class="card-img-top" alt="{{ $cerita->judul }}">
                                                <div class="card-body">
                                                    <input type="text" value="{{ $cerita->getNamaKategoriByWaktu($key) }}">
                                                 
                                                    <p class="card-text">{{ $cerita->isi }}</p>
                                                    <p class="card-text"><small class="text-muted">{{ $cerita->kategoris->pluck('nama')->join(', ') }}</small></p>
                                                </div> -->
                                                <div class="row">
                                                    <!-- left column -->
                                                    <div class="col-md-3">
                                                        <div class="text-center">
                                                            <img src="https://png.pngitem.com/pimgs/s/150-1503945_transparent-user-png-default-user-image-png-png.png" class="avatar img-circle" alt="avatar">
                                                            <h6>Upload a different photo...</h6>

                                                            <input type="file" class="form-control">
                                                        </div>
                                                    </div>

                                                    <!-- edit form column -->
                                                    <div class="col-md-9 personal-info">
                                                        <div class="alert alert-info alert-dismissable">
                                                            <a class="panel-close close" data-dismiss="alert">×</a>
                                                            <i class="fa fa-coffee"></i>
                                                            This is an <strong>.alert</strong>. Use this to show important messages to the user.
                                                        </div>
                                                        <h3>Personal info</h3>

                                                        <form class="form-horizontal" role="form">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Username:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" type="text" value="janeuser">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-lg-3 control-label">Email:</label>
                                                                <div class="col-lg-8">
                                                                    <input class="form-control" type="text" value="janesemail@gmail.com">
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Password:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" type="password" value="11111122333">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Confirm password:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" type="password" value="11111122333">
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
                                            @empty
                                            <div class="row">
      <!-- left column -->
      <div class="col-md-3">
        <div class="text-center">
          <img src="https://png.pngitem.com/pimgs/s/150-1503945_transparent-user-png-default-user-image-png-png.png" class="avatar img-circle" alt="avatar">
          <h6>Upload a different photo...</h6>
          
          <input type="file" class="form-control">
        </div>
      </div>
      
      <!-- edit form column -->
      <div class="col-md-9 personal-info">

        <h3>Personal info</h3>
        
        <form class="form-horizontal" role="form">
<div class="form-group">
            <label class="col-md-3 control-label">Username:</label>
            <div class="col-md-8">
              <input class="form-control" type="text" value="janeuser">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Email:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="janesemail@gmail.com">
            </div>
          </div>
         
          
          <div class="form-group">
            <label class="col-md-3 control-label">Password:</label>
            <div class="col-md-8">
              <input class="form-control" type="password" value="11111122333">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Confirm password:</label>
            <div class="col-md-8">
              <input class="form-control" type="password" value="11111122333">
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
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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