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
                    <a class="btn btn-primary me-2" href="{{route('users.deleteLog')}}" role="button">Lihat Log User Yang DIhapus</a>
                    <a class="btn btn-primary" href="{{route('users.create')}}" role="button">Tambah user</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $kt)
                            <tr>

                                <td>{{$kt->name}}</td>
                                <td>
                                    <span class="badge bg-label-primary me-1">
                                        @foreach($kt->roles as $role)
                                        {{ $role->name }}@if (!$loop->last), @endif
                                        @endforeach
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-success me-1">
                                        {{$kt->status}}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Action buttons">
                                        <a href="{{ route('users.edit', $kt->id) }}" class="btn btn-warning">Edit</a>
                                        <button class="btn btn-info btn-show" data-id="{{ $kt->id }}">Show</button>
                                        <button class="btn btn-danger btn-delete" data-name="{{ $kt->name }}" data-id="{{ $kt->id }}">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Users Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <span id="userName"></span></p>
                        <p><strong>Role:</strong> <span class="badge bg-label-primary me-1" id="userRole"></span></p>
                        <p><strong>Status:</strong> <span class="badge bg-label-success me-1" id="userStatus"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>User Name:</strong> <span id="userUsername"></span></p>
                        <p><strong>Nomor Hp:</strong> <span id="userNohp"></span></p>
                    </div>
                </div>
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
            $.get('/users/' + id, function(data) {
                $('#userName').text(data.name);
                $('#userUsername').text(data.username);
                $('#userNohp').text(data.no_hp);
                $('#userStatus').text((data.status ? 'Approved' : 'Pending'));
                var roles = data.roles.map(role => role.name).join(', ');
                $('#userRole').text(roles);
                $('#userModal').modal('show');
            });
        });
    });
</script>

<script>
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `Data user  Dengan Nama <span style="color: red;"> (${name})</span>  akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/' + id,
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