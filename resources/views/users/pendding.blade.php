@extends('layouts.app')

@section ('content')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> User Pandding</h4>
        <!-- <hr class="my-5" /> -->
        <!-- Bordered Table -->
        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <h2 class="tmbh-data-title">Table User Pandding</h2>
                    </div>
                </div>
                <div class="col">
                <div class="d-flex container-tmbh-data">
                <a class="btn btn-primary" href="{{route('users.deleteLog')}}" role="button">Lihat Log User Yang DIhapus</a>
                </div>
            </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table id="users-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>

@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('fetch.users') }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'roles', name: 'roles' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
            emptyTable: "Data user pending belum ada" // Ganti pesan di sini
        }
        });
    });

         // Definisikan fungsi refreshUserTable
         window.refreshUserTable = function() {
            $('#users-table').DataTable().ajax.reload(); // Refresh table data
        };

            // Event listener for new user registration
          

            function updateStatus(element) {
    const id = element.getAttribute('data-id');
    const username = element.getAttribute('data-username');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: 'Anda yakin?',
        html: `Anda akan mengubah status pending menjadi approved pada username <span style="color: red;">${username}</span>.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/update-status/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: 'approved'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Berhasil!',
                        `Status untuk <span style="color: red;">${username}</span> berhasil diubah.`,
                        'success'
                    );
                    $('#users-table').DataTable().ajax.reload(); // Refresh table data
                } else {
                    Swal.fire(
                        'Gagal!',
                        'Gagal mengubah status.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Terjadi kesalahan!',
                    'Terjadi kesalahan saat mengubah status.',
                    'error'
                );
            });
        }
    });
}




    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `Data user Dengan Nama <span style="color: red;"> (${name})</span> akan dihapus secara permanen!`,
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
                            $('#users-table').DataTable().ajax.reload(); // Refresh table data
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
