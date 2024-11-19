@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Edit Video</h4>

        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Video</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form untuk edit video -->
                        <form id="video-edit-form" enctype="multipart/form-data" method="POST" action="{{ route('videos.update', $video->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="selected-text-name">Nama Video</label>
                                <input type="text" name="title" class="form-control" id="selected-text-name" value="{{ $video->title }}" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="selected-text">Deskripsi</label>
                                <textarea id="selected-text" name="description" class="form-control" placeholder="Deskripsi Video">{{ $video->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="video-file" class="form-label">Ganti Video (mp4, mkv, avi):</label>
                                <input type="file" name="video_file" id="video-file" class="form-control" accept="video/*" onchange="handleVideoSelection(event)">
                            </div>

                            <!-- Tampilkan video lama jika ada -->
                            @if ($video->create_by === 'file_upload')
                            <div class="mb-3" id="old-video-container">
                                <label class="form-label">Video Lama:</label>
                                <video width="100%" controls>
                                    <source src="{{ asset('storage/videos/' . $video->video_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung video HTML5.
                                </video>
                            </div>
                            @elseif ($video->create_by === 'link')
                            <div class="mb-3">
                                <label class="form-label">Link Video:</label>
                                <a href="{{ $video->video_path }}" target="_blank">{{ $video->video_path }}</a>
                            </div>
                            @endif

                            <!-- Tombol Preview Modal -->
                            <button type="button" id="preview-button" class="btn btn-secondary" style="display: none;" data-bs-toggle="modal" data-bs-target="#videoPreviewModal">
                                Pratinjau Video
                            </button>
                            <br><br>

                            <!-- Tombol Update -->
                            <button type="submit" onclick="editVideo()" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('videos.index') }}" class="btn btn-warning">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
</div>

<!-- Modal untuk preview video -->
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
<!-- Modal untuk progress bar -->
<div class="modal fade" id="uploadProgressModal" tabindex="-1" aria-labelledby="uploadProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadProgressModalLabel">Mengunggah Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" disabled></button>
            </div>
            <div class="modal-body">
                <label for="upload-progress" class="form-label">Proses Pengunggahan:</label>
                <div class="progress">
                    <div id="upload-progress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    document.getElementById('video-file').addEventListener('change', function () {
    const oldVideoContainer = document.getElementById('old-video-container');
    if (oldVideoContainer) {
        oldVideoContainer.style.display = 'none'; // Sembunyikan video lama
    }
});

function editVideo() {
    const fileInput = document.getElementById('video-file');
    const file = fileInput.files[0];

    const formData = new FormData();
    if (file) {
        formData.append('video_file', file);
    }

    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT'); // Metode PUT untuk update
    formData.append('title', document.getElementById('selected-text-name').value);
    formData.append('description', document.getElementById('selected-text').value);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', `{{ route('videos.update', $video->id) }}`, true); // Menggunakan URL update video

    // Tampilkan modal progress
    const uploadProgressModal = new bootstrap.Modal(document.getElementById('uploadProgressModal'));
    uploadProgressModal.show();

    // Tampilkan progress bar
    const progressBar = document.getElementById('upload-progress');
    progressBar.style.width = '0%';
    progressBar.setAttribute('aria-valuenow', 0);
    progressBar.textContent = '0%';

    // Event listener untuk update progress
    xhr.upload.addEventListener('progress', function (event) {
        if (event.lengthComputable) {
            const percentComplete = Math.round((event.loaded / event.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressBar.setAttribute('aria-valuenow', percentComplete);
            progressBar.textContent = percentComplete + '%';
        }
    });

    xhr.addEventListener('load', function () {
        uploadProgressModal.hide(); // Sembunyikan modal progress
        if (xhr.status >= 200 && xhr.status < 300) {
            Swal.fire('Berhasil', 'Video berhasil diperbarui!', 'success').then(() => {
                window.location.href = '{{ route('videos.index') }}';
            });
        } else {
            Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui video.', 'error');
        }
    });

    // Event listener untuk menangani error
    xhr.addEventListener('error', function () {
        uploadProgressModal.hide(); // Sembunyikan modal progress
        Swal.fire('Gagal', 'Gagal memperbarui video. Silakan coba lagi.', 'error');
    });

    // Kirim form
    xhr.send(formData);
}


</script>
@endpush
