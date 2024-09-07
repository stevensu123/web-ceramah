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
                                        <a href="#" class="btn-show fav-btn" data-id="{{ $item['role']->id }}">
                                            <i class="bx bxs-show"></i>
                                        </a>
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



<!-- Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Role Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="role-image" src="" alt="Role Image" style="width: 250px; height: auto;">

                <p id="role-name"></p>
                <p id="role-totalPermission"></p>
                <div id="role-authoritiesPermission" class="pcard-info">
                    <!-- Permissions will be dynamically added here -->
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
    var scrollTop; // Variabel global untuk menyimpan posisi scroll

    // Simpan posisi scroll saat ini dan nonaktifkan scroll pada body
    function disableScroll() {
        // Simpan posisi scroll saat ini
        scrollTop = $(window).scrollTop();
        $('body').data('scrollTop', scrollTop);

        // Nonaktifkan scroll pada body
        $('body').css({
            'position': 'fixed',
            'top': -scrollTop,
            'left': 0,
            'width': '100%'
        });
    }

    // Aktifkan kembali scroll pada body dan kembalikan posisi scroll
    function enableScroll() {
        // Kembalikan posisi scroll
        $(window).scrollTop(scrollTop);

        // Aktifkan kembali scroll pada body
        $('body').css({
            'position': '',
            'top': '',
            'left': '',
            'width': ''
        });
    }

    // Event handler untuk membuka modal
    $('.btn-show').click(function() {
        var id = $(this).data('id');
        disableScroll(); // Nonaktifkan scroll

        // Ambil data untuk modal
        $.get('/roles/' + id, function(data) {
            $('#role-name').text('Role Name: ' + data.name);
            $('#role-totalPermission').text('Total Permissions: ' + data.total_permissions);

            // Load permissions dynamically
            var permissionsHtml = '';
            $.each(data.authorities, function(category, perms) {
                var hasPermissions = perms.some(perm => data.permissions.includes(perm));
                if (hasPermissions) {
                    permissionsHtml += '<div class="info-item">';
                    permissionsHtml += '<span class="pcard-title-txt">' + capitalizeFirstLetter(category.replace(/_/g, ' ')) + '</span>';
                    permissionsHtml += '<ul>';
                    $.each(perms, function(index, perm) {
                        if (data.permissions.includes(perm)) {
                            permissionsHtml += '<li>' + capitalizeFirstLetter(perm.replace(/_/g, ' ')) + '</li>';
                        }
                    });
                    permissionsHtml += '</ul>';
                    permissionsHtml += '</div>';
                }
            });
            $('#role-authoritiesPermission').html(permissionsHtml);

            // Update image src if available
            $('#role-image').attr('src', data.image ? '/storage/upload/emotikon/' + data.image : '');

            $('#roleModal').modal('show');
        });
    });

    // Event handler untuk menutup modal
    $('#roleModal').on('hidden.bs.modal', function() {
        // Tidak perlu memanggil enableScroll di sini jika ingin scroll tetap di posisi saat modal ditutup
    });

    // Event handler untuk tombol close
    $('#roleModal').on('hide.bs.modal', function() {
        enableScroll(); // Aktifkan kembali scroll jika tombol close di klik
    });
});

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

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