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
                        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Nama Video</label>
                                <input type="text" name="title" class="form-control" id="selected-text-name" placeholder="Nama Video" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Deskripsi</label>
                                <textarea id="selected-text" name="description" class="form-control" placeholder="Deskripsi Video"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="video-file" class="form-label">Upload Video (mp4, mkv, avi):</label>
                                <input type="file" name="video_file" id="video-file" class="form-control" accept="video/*" onchange="handleVideoSelection(event)">
                            </div>

                            <!-- Tombol Preview Modal -->
                            <button type="button" id="preview-button" class="btn btn-secondary" style="display: none;" data-bs-toggle="modal" data-bs-target="#videoPreviewModal">
                                Pratinjau Video
                            </button>

                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Atau Masukkan Link YouTube:</label>
                                <input type="url" name="video_link" class="form-control" id="selected-text-name" placeholder="Masukkan Link YouTube" />
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

<div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoPreviewModalLabel">Pratinjau Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Informasi Video -->
                <p id="video-name"></p>
                <p id="video-size"></p>
                <p id="video-format"></p>
                
                <!-- Video Player -->
                <video id="video-preview" width="100%" controls>
                    <source id="video-source">
                    Browser Anda tidak mendukung video HTML5.
                </video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<!-- <script>
       
       var table = $('#kategori-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("kategori.index") }}',
            columns: [
                { data: 'nama_kategori', name: 'nama_kategori' },
                { data: 'gambar', name: 'gambar' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

</script> -->
<script>
    function handleVideoSelection(event) {
        const file = event.target.files[0];
        const previewButton = document.getElementById('preview-button');
        const videoSource = document.getElementById('video-source');
        const videoPreview = document.getElementById('video-preview');
        const videoNameElement = document.getElementById('video-name');
        const videoSizeElement = document.getElementById('video-size');
        const videoFormatElement = document.getElementById('video-format');

        if (file) {
            const fileURL = URL.createObjectURL(file);
            videoSource.src = fileURL;
            videoPreview.load();
            previewButton.style.display = 'inline-block'; // Tampilkan tombol preview jika file dipilih

            // Update informasi file
            videoNameElement.textContent = `Nama: ${file.name}`;
            videoSizeElement.textContent = `Ukuran: ${(file.size / (1024 * 1024)).toFixed(2)} MB`; // Ukuran dalam MB
            videoFormatElement.textContent = `Format: ${file.type}`;
        } else {
            previewButton.style.display = 'none'; // Sembunyikan tombol preview jika tidak ada file
        }
    }
</script>

@endsection