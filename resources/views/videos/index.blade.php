@extends('layouts.app')

@section ('content')
<style>
    /* Mengubah warna latar belakang dan teks di thead */
    #videos-table thead {
        background-color: black;
        /* Ganti dengan warna yang diinginkan */
        color: white;
        /* Ganti dengan warna teks yang diinginkan */
    }

    #videos-table thead th {
        color: white;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        padding: 8px 16px;
        text-transform: uppercase;
    }

    /* Mengubah warna dropdown length menu */

    .dataTables_length {
        max-width: 330px;
        margin: 50px auto;
    }

    .dataTables_length label {
        font-weight: bold;
        /* Membuat label lebih menonjol */
    }

    .select-menu {
        max-width: 200px;

        position: relative;
        /* Penting untuk dropdown */
    }

    .select-menu .select-area {
        cursor: pointer;
        /* Menjadikan area seleksi bisa diklik */
    }

    .select-menu .select-btn {
        display: flex;
        height: 55px;
        background: #fff;
        padding: 20px;
        font-size: 14px;
        font-weight: 400;
        border-radius: 8px;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    /* Mengatur area dropdown */
    .select-menu .options {
        position: absolute;
        width: 330px;
        overflow-y: auto;
        max-height: 295px;
        padding: 10px;
        margin-top: 10px;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: none;
        /* Menyembunyikan dropdown awalnya */
    }

    .select-menu.active .options {
        display: block;
        /* Menampilkan dropdown saat aktif */
        opacity: 0;
        z-index: 10;
        animation-name: fadeInUp;
        animation-duration: 0.4s;
        animation-fill-mode: both;
    }

    /* Menjaga opsi dropdown konsisten */
    .select-menu .options .option {
        display: flex;
        height: 55px;
        cursor: pointer;
        padding: 0 16px;
        border-radius: 8px;
        align-items: center;
        background: #fff;
    }

    .select-menu .options .option:hover {
        background: #f2f2f2;
    }

    .select-menu .options .option i {
        font-size: 25px;
        margin-right: 12px;
    }

    .select-menu .options .option .option-text {
        font-size: 18px;
        color: #333;
    }

    .select-btn i {
        font-size: 25px;
        transition: 0.3s;
    }

    .select-menu.active .select-btn i {
        transform: rotate(-180deg);
    }

    @keyframes fadeInUp {
        from {
            transform: translate3d(0, 30px, 0);
        }

        to {
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
    }
/* Mengatur gaya umum untuk pagination */
.dataTables_wrapper .dataTables_paginate {
    display: flex;
    justify-content: center; /* Pusatkan tombol */
    margin: 20px 0;
}

/* Gaya untuk tombol pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 10px 15px;
    margin: 0 5px; /* Jarak antar tombol */
    border: none; /* Menghilangkan border default */
    border-radius: 5px; /* Sudut membulat */
    background-color: #007bff; /* Warna latar belakang */
    color: white; /* Warna teks */
    font-weight: bold; /* Membuat teks lebih tebal */
    transition: background-color 0.3s, transform 0.2s; /* Transisi halus */
    cursor: pointer; /* Menunjukkan bahwa tombol bisa diklik */
}

/* Gaya untuk tombol aktif */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #0056b3; /* Warna latar belakang aktif */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efek bayangan */
}

/* Gaya saat hover */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #0056b3; /* Warna saat hover */
    transform: scale(1.1); /* Membesarkan sedikit saat hover */
}

/* Gaya untuk tombol disabled */
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #ccc; /* Warna teks untuk tombol yang dinonaktifkan */
    background-color: #f1f1f1; /* Warna latar belakang untuk tombol yang dinonaktifkan */
    cursor: not-allowed; /* Menunjukkan bahwa tombol tidak bisa diklik */
}

/* Gaya untuk input jumlah tampilan */
.dataTables_wrapper .dataTables_length select {
    margin-left: 20px; /* Jarak antara tombol pagination dan select */
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #007bff; /* Warna border */
    color: #333;
}

/* Gaya untuk input pencarian */
.dataTables_wrapper .dataTables_filter input {
    margin-left: 20px; /* Jarak antara label pencarian dan input */
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #007bff; /* Warna border */
    color: #333;
}


</style>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Kategori</h4>
        <!-- <hr class="my-5" /> -->
        <!-- Bordered Table -->
        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <h2 class="tmbh-data-title">Bordered Table</h2>
                    </div>

                </div>
                <div class="col">
                    <div class="d-flex container-tmbh-data">
                        <!-- Tombol Tambah Video -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chooseVideoModal">
                            Tambah Video
                        </button>

                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">

                <div class="select-menu  me-3">
                    <div class="select-area">
                        <div class="select-btn">
                            <span class="sBtn-text">Pilih Jumlah Data</span>
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                    <ul class="options">
                        <li class="option" data-value="all">
                            <span class="option-text">Semua Data</span>
                        </li>
                        <li class="option" data-value="5">
                            <span class="option-text">5</span>
                        </li>
                        <li class="option" data-value="10">
                            <span class="option-text">10</span>
                        </li>
                        <li class="option" data-value="15">
                            <span class="option-text">15</span>
                        </li>
                        <li class="option" data-value="100">
                            <span class="option-text">100</span>
                        </li>
                        <li class="option" data-value="500">
                            <span class="option-text">500</span>
                        </li>
                    </ul>
                </div>

                {!! $dataTable->table() !!} <!-- Panggil table dari DataTable -->
            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>

<!-- Modal Pilihan Tambah Video -->
<div class="modal fade" id="chooseVideoModal" tabindex="-1" aria-labelledby="chooseVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chooseVideoModalLabel">Pilih Cara Menambah Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Silakan pilih metode untuk menambahkan video:</p>
                <div class="d-grid gap-2">
                    <!-- Pilihan 1: Tambah Video dari File -->
                    <a href="{{ route('videos.create.file') }}" class="btn btn-outline-primary">
                        Tambah Video dari File
                    </a>
                    <!-- Pilihan 2: Tambah Video dari Link YouTube -->
                    <a href="{{ route('videos.create.youtube') }}" class="btn btn-outline-primary">
                        Tambah Video dari Link YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoPreviewModalLabel">Pratinjau Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="video-name">Nama Video :</p>
                <p id="video-size">Ukuran Video :</p>
                <p id="video-format">Format Video :</p>
                <video id="video-preview" width="100%" controls>
                    <source id="video-source" src="">
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

@push('script')
{!! $dataTable->scripts() !!}
@if (session('showModal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('chooseVideoModal'));
        modal.show();
    });
    document.querySelectorAll('.select-area').forEach(item => {
        item.addEventListener('click', event => {
            const parentSelect = item.closest('.select-menu');
            parentSelect.classList.toggle('active');
        });
    });

    // Menutup dropdown jika klik di luar area
    document.addEventListener('click', function(event) {
        const selectMenu = document.querySelector('.select-menu');
        if (!selectMenu.contains(event.target)) {
            selectMenu.classList.remove('active');
        }
    });

    // Menangani pemilihan opsi
    const options = document.querySelectorAll('.option');
    const selectBtn = document.querySelector('.select-btn');
    const sBtn_text = selectBtn.querySelector('.sBtn-text');

    options.forEach(option => {
        option.addEventListener('click', () => {
            let selectedOption = option.querySelector('.option-text').innerText;
            sBtn_text.innerText = selectedOption;
            selectMenu.classList.remove('active');
        });
    });
</script>
@endif
<script>
    function showVideoDetails(videoId) {
        console.log("Video ID: ", videoId);
        $.ajax({
            url: '/videos/' + videoId,
            type: 'GET',
            success: function(data) {
                console.log(data); // Debug respons di console
                if (data.error) {
                    alert(data.error);
                    return;
                }
                // Mengisi data ke dalam modal
                $('#video-name').text('Nama Video: ' + data.title); // Menambahkan label "Nama Video"
                $('#video-size').text('Ukuran Video: ' + (data.size / (1024 * 1024)).toFixed(2) + ' MB'); // Menambahkan label "Ukuran Video"
                $('#video-format').text('Format Video: ' + data.format.toUpperCase()); // Menambahkan label "Format Video"
                $('#video-source').attr('src', '/storage/videos/' + encodeURIComponent(data.video_path));
                $('#video-preview')[0].load();
                $('#videoPreviewModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error: ', xhr.responseText);
                alert('Terjadi kesalahan saat mengambil data video.');
            }
        });
    }
</script>

<script>
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var title = $(this).data('title'); // Mengambil judul video dari atribut data-title

        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: 'Video <span style="color: red; font-weight: bold;">"' + title + '"</span> akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/videos/' + id, // Pastikan URL sesuai dengan resource 'videos'
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') // Mengambil CSRF Token dari meta tag
                    },
                    success: function(response) {
                        Swal.fire(
                            'Terhapus!',
                            'Video "' + title + '" berhasil dihapus.',
                            'success'
                        ).then(() => {
                            $('#videos-table').DataTable().ajax.reload(); // Reload DataTable
                        });
                    },
                    error: function(response) {
                        Swal.fire(
                            'Gagal!',
                            'Video "' + title + '" gagal dihapus.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>

@endpush