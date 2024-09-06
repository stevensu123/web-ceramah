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
                        <a class="btn btn-primary" href="{{route('kategori.create')}}" role="button">Tambah Kategori</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
            <div class="pcard-container">
    @forelse($data as $item)
        <div class="pcard">
            <div class="pcard-row">
                <div class="pcard-content">
                    <div class="pcard-header">
                        <div class="pcard-details">
                            <span class="card-headline-text">{{ $item['role']->name }}</span>
                            <span class="card-caption">Prathik Developers, Bengaluru</span>
                        </div>
                        <div class="pcard-actions">
                            <button class="discount-btn">10%</button>
                            <button class="fav-btn">
                                <i class="material-icons">favorite_border</i>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="pcard-info">
                        <div class="info-item">
                            <span class="pcard-title-txt">Total Permission</span>
                            <span class="pcard-title-caption">{{ $item['total_permissions'] }}</span>
                        </div>
                    </div>

                    <!-- Display Permissions by Category -->
                    <div class="pcard-info">
                        @foreach($item['authorities'] as $category => $perms)
                            @php
                                // Check if the role has any permissions in this category
                                $hasPermissions = !empty(array_intersect($perms, $item['permissions']));
                            @endphp

                            @if($hasPermissions)
                                <div class="info-item">
                                    <span class="pcard-title-txt">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                                    <ul>
                                        @foreach($perms as $perm)
                                            @if(in_array($perm, $item['permissions']))
                                                <li>{{ ucfirst(str_replace('_', ' ', $perm)) }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="pcard-footer">
                        <button class="quick-btn">QUICK BOOK</button>
                        <button class="explore-btn">ADD TO CART</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p>No roles found</p>
    @endforelse
</div>


            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>

<div class="modal fade" id="KategoriModal" tabindex="-1" aria-labelledby="KategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="KategoriModalLabel">Kategori Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="Kategori-image" src="" alt="Product Image" style="width: 250px; height: auto;">

                <p id="Kategori-name"></p>
                <p id="Kategori-description"></p>
                <span class="badge bg-label-primary me-1" id="Kategori-Status"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    $(document).ready(function() {
        $('.btn-show').click(function() {
            var id = $(this).data('id');
            $.get('/kategori/' + id, function(data) {
                $('#Kategori-name').text('Nama Kategori: ' + data.nama_kategori);
                $('#Kategori-description').text('Desktripsi: ' + data.keterangan);
                $('#Kategori-Status').text('Status: ' + (data.status ? 'Active' : 'Inactive'));
                $('#Kategori-image').attr('src', '/storage/upload/emotikon/' + data.gambar);
                $('#KategoriModal').modal('show');
            });
        });
    });
</script>

<script>
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/kategori/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(response) {
                        Swal.fire(
                            'Gagal!',
                            'Data gagal dihapus.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>
@endsection