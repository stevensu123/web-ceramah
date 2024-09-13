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
                        <a class="btn btn-primary" href="{{route('users.create')}}" role="button">Tambah Kategori</a>
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
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Inisialisasi Pusher dengan key dan cluster yang benar
    var pusher = new Pusher('a01db3fdb4e4ae7a7ada', {
        cluster: 'ap1',
        encrypted: true
    });

    // Berlangganan pada channel
    var channel = pusher.subscribe('admin-channel');

    // Listen event ketika user baru mendaftar
    channel.bind('App\\Events\\UserRegistered', function(data) {
        console.log('Received data:', data); // Debugging: Cek data yang diterima

        // Refresh DataTable
        $('#users-table').DataTable().ajax.reload(); // Refresh table data

      
    });
</script>

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
            ]
        });
    });

          // Function to refresh the DataTable
          function refreshTable() {
                table.ajax.reload();
            }

            // Event listener for new user registration
          

    function updateStatus(element) {
        const id = element.getAttribute('data-id');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!confirm('Anda yakin ingin mengubah status?')) {
            return;
        }

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
                    $('#users-table').DataTable().ajax.reload(); // Refresh table data
                } else {
                    alert('Gagal mengubah status!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan!');
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
