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
                        <a class="btn btn-primary" href="{{route('roles.create')}}" role="button">Tambah Role</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <form action="{{ route ('roles.update',['role'=> $role->id])}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="input_role_name" class="font-weight-bold">
                                            Role name
                                        </label>
                                        <input id="input_role_name" value="{{old('name', $role->name)}}" name="name" type="text" 
                                        class="form-control @error('name') is-invalid @enderror"  />
                                        @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{$message}}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="input_role_permission" class="font-weight-bold">
                                            Permission
                                        </label>
                                        <div class="form-control overflow-auto h-100 @error('permissions') is-invalid @enderror" id="input_role_permission">
                                            <!-- Flex container untuk manage name -->
                                            <div class="d-flex flex-row justify-content-start">
                                                @foreach ($authorities as $manageName => $permissions)
                                                <!-- Setiap manage name dan permissions -->
                                                <div class="mx-2">
                                                    <!-- Judul Manage (manage_kategori / manage_user) -->
                                                    <div class="list-group-item bg-dark text-white text-center mb-2">
                                                        {{$manageName}}
                                                    </div>

                                                    <!-- Permissions (kategori_show, kategori_create) ditampilkan ke bawah -->
                                                    <ul class="list-group">
                                                        @foreach ($permissions as $permission)
                                                        <li class="list-group-item">
                                                            <div class="form-check">
                                                                @if(old('permissions', $permissionChecked))
                                                                <input id="{{$permission}}" name="permissions[]" class="form-check-input" type="checkbox"
                                                                value="{{$permission}}" {{in_array($permission,old('permissions', $permissionChecked)) ? "checked" : null}}>
                                                                @else
                                                                <input id="{{$permission}}" name="permissions[]" class="form-check-input" type="checkbox"
                                                                value="{{$permission}}" >
                                                                @endif
                                                                <label for="{{$permission}}" class="form-check-label">
                                                                    {{$permission}}
                                                                </label>
                                                            </div>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('permissions')
                                        <span class="invalid-feedback">
                                            <strong>{{$message}}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="float-right mb-4">
                                        <a class="btn btn-warning px-4 mx-2" href="{{route('roles.index')}}">
                                            Back
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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