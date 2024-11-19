@extends('layouts.app')

@section('content')
<div class="content-wrapper">
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
                                <label class="form-label" for="basic-default-fullname">Atau Masukkan Link YouTube:</label>
                                <input type="url" name="video_link" class="form-control" id="youtube-link" placeholder="Masukkan Link YouTube" />
                            </div>

                            <button type="button" class="btn btn-info" id="preview-button" style="display: none;" data-bs-toggle="modal" data-bs-target="#youtubeModal">Pratinjau Video</button>

                            <button type="submit" class="btn btn-primary">Tambah Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Pratinjau Video -->
    <div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="youtubeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="youtubeModalLabel">Pratinjau Video YouTube</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="youtube-preview"></div>
                    <hr>
                    <div id="channel-details" class="channel-info"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('youtube-link').addEventListener('input', function() {
        const link = this.value;
        const previewButton = document.getElementById('preview-button');
        const preview = document.getElementById('youtube-preview');
        const channelDetails = document.getElementById('channel-details');

        // Cek apakah link YouTube valid
        const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = link.match(youtubeRegex);

        if (match) {
            const videoId = match[1];
            const embedUrl = `https://www.youtube.com/embed/${videoId}`;

            // Set iframe untuk pratinjau video
            preview.innerHTML = `<iframe width="100%" height="315" src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;

            // Menampilkan tombol pratinjau
            previewButton.style.display = 'block';

            // Mengambil informasi video menggunakan RapidAPI
            const apiKey = 'ceb237eca5msh9a17ac8d50bc1b0p191bb0jsn9a122a125061'; // Ganti dengan kunci API kamu
            const apiHost = 'youtube-v31.p.rapidapi.com';

            // Ambil detail video dan channel
            fetch(`https://youtube-v31.p.rapidapi.com/videos?part=contentDetails,snippet,statistics&id=${videoId}`, {
                method: 'GET',
                headers: {
                    'x-rapidapi-key': apiKey,
                    'x-rapidapi-host': apiHost
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.items && data.items.length > 0) {
                    const videoData = data.items[0];
                    const channelId = videoData.snippet.channelId;
                    const likeCount = Number(videoData.statistics.likeCount).toLocaleString() || "N/A"; // Mengubah ke format angka dengan pemisah ribuan

                    // Tampilkan informasi video dan channel
                    channelDetails.innerHTML = `
                        <div class="channel-details">
                            <p><strong>Nama Channel:
                            </strong> ${videoData.snippet.channelTitle}</p>
                            <p class="like-count">
                            <strong>Jumlah Like:</strong> ${likeCount} Likes</p>
                        </div>
                        <div class="subscriber-count">
                            <strong>Jumlah Subscribe:</strong> ${Number(videoData.statistics.subscriberCount).toLocaleString()} Subscriber
                        </div>
                    `;

                    // Ambil detail channel (hanya satu kali)
                    fetch(`https://youtube-v31.p.rapidapi.com/channels?part=snippet,statistics&id=${channelId}`, {
                        method: 'GET',
                        headers: {
                            'x-rapidapi-key': apiKey,
                            'x-rapidapi-host': apiHost
                        }
                    })
                    .then(channelResponse => {
                        if (!channelResponse.ok) {
                            throw new Error(`Error: ${channelResponse.status} ${channelResponse.statusText}`);
                        }
                        return channelResponse.json();
                    })
                    .then(channelData => {
                        if (channelData && channelData.items && channelData.items.length > 0) {
                            const channelInfo = channelData.items[0];

                            // Update subscriber count jika berbeda
                            const subscriberCountElem = channelDetails.querySelector('.subscriber-count');
                            if (subscriberCountElem) {
                                subscriberCountElem.innerHTML = `<strong>Jumlah Subscribe:</strong> ${Number(channelInfo.statistics.subscriberCount).toLocaleString()} Subscriber`;
                            }
                        } else {
                            channelDetails.innerHTML += '<p>Detail channel tidak ditemukan.</p>';
                        }
                    })
                    .catch(channelError => {
                        console.error('Error fetching channel details:', channelError);
                        channelDetails.innerHTML += '<p>Gagal mengambil detail channel.</p>';
                    });

                } else {
                    channelDetails.innerHTML = '<p>Video tidak ditemukan.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching video details:', error);
                channelDetails.innerHTML = '<p>Gagal mengambil detail video.</p>';
            });

        } else {
            previewButton.style.display = 'none';
            preview.innerHTML = '';
            channelDetails.innerHTML = '';
        }
    });
</script>

<style>
    .channel-info {
        display: flex;
        flex-direction: column; /* Menempatkan elemen secara vertikal */
        padding: 10px;
        border: 1px solid #ccc; /* Garis batas untuk pemisahan visual */
        border-radius: 8px; /* Sudut yang lebih halus */
        background-color: #f9f9f9; /* Warna latar belakang */
        margin: 10px 0; /* Jarak atas dan bawah */
    }

    .channel-details {
        display: flex;
        justify-content: space-between; /* Memisahkan konten ke kiri dan kanan */
        align-items: center; /* Menyelaraskan konten secara vertikal */
    }

    .channel-name {
        font-size: 1.5em; /* Ukuran font untuk nama channel */
        margin: 0; /* Menghapus margin default */
    }

    .subscriber-count, .like-count {
        font-size: 1em; /* Ukuran font untuk subscriber dan likes */
        color: #555; /* Warna teks yang lebih gelap untuk kontras */
    }
</style>
@endsection
