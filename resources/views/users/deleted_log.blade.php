@extends('layouts.app')

@section ('content')

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
                        @if($hasDeletedUsers)
                        <a href="{{ route('clear.deleted.users.log') }}" id="confirm-clear-log" class="btn btn-danger me-2">Bersihkan Log</a>
                        @endif
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Kembali</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div id="example" class="container">
                    <h1>Daftar Pengguna yang Dihapus</h1>

                    @if($deletedUsers->isEmpty())
                    <p>Tidak ada data pengguna yang dihapus.</p>
                    @else
                    <ul class="list-group">
                        @foreach($deletedUsers as $user)
                        <li class="list-group-item">
                            <strong>ID:</strong> {{ $user->id }}<br>
                            <strong>Nama:</strong> {{ $user->name }}<br>
                            <strong>Username:</strong> {{ $user->username ?? 'Tidak tersedia' }}<br>
                            <strong>Roles:</strong> <span style="color: skyblue;">{{ implode(', ', $user->roles) }}</span><br>
                            <strong>Status:</strong> <span style="color: red;">{{ $user->status }}</span><br>
                            <strong>Dihapus pada:</strong> {{ \Carbon\Carbon::parse($user->deleted_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}<br>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>

@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '#confirm-clear-log', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Semua log pengguna yang dihapus akan dibersihkan secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, bersihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Terhapus!',
                            'Log berhasil dibersihkan.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(response) {
                        Swal.fire(
                            'Gagal!',
                            'Log gagal dibersihkan.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>
@endsection