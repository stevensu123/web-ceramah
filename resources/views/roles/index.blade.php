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
                                        <span class="card-caption">{{ $item['description'] }}</span>
                                    </div>
                                    <div class="pcard-actions">
                                        <a href="#" class="btn-show fav-btn" data-id="{{ $item['role']->id }}">
                                            <i class="bx bxs-show"></i>
                                            Lihat Detail
                                        </a>

                                    </div>
                                </div>
                                <hr>
                                <div class="pcard-info">
                                    <div class="info-item">
                                        <span class="pcard-title-txt">Total Permission :</span>
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
                                    <div class="info-item ">
                                            
                                        <span class="pcard-title-txt-manage"  style="background-color: black; color: white; padding: 5px; margin-left:5px; display: inline-block;">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                                        <ul class="list-group" style="padding-left:5px; padding-top:5px;">
                                            @foreach($perms as $perm)
                                            @if(in_array($perm, $item['permissions']))
                                            <li class="list-group-item">{{ ucfirst(str_replace('_', ' ', $perm)) }}</li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>

                                <div class="pcard-footer">
                                    <a href="{{ route('roles.edit', $item['role']->id) }}" class="btn btn-warning edit-btn ">
                                    <i class='bx bx-edit-alt'></i>
                                    Edit
                                    </a>
                                    <form action="{{ route('roles.destroy', $item['role']->id) }}" method="POST" role="alert"
                                        alert-text="Apakah Anda yakin ingin menghapus role  {{ $item['role']->name }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger delete-btn ">
                                        <i class="bx bx-trash me-1"></i>
                                        Delete
                                    </button>
                                    </form>
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
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Role Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="role-image" src="" alt="Role Image" style="width: 250px; height: auto;">

                <p  id="role-name"></p>
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

<script>
    $(document).ready(function() {
        // Event handler untuk membuka modal
        $('.btn-show').click(function(event) {
            event.preventDefault(); // Mencegah halaman scroll ke atas

            var id = $(this).data('id');

            // Ambil data dari backend yang sudah dalam bentuk HTML
            $.get('/roles/' + id, function(data) {
                $('#role-name').text('Role Name: ' + data.name);
                $('#role-totalPermission').text('Total Permissions: ' + data.total_permissions);
                // Langsung load HTML dari backend
                $('#role-authoritiesPermission').html(data.permissionsHtml);
                $('#roleModal').modal('show');
            });
        });


        $("form[role='alert']").submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Hapus Data",
                text: $(this).attr('alert-text'),
                icon: 'warning',
                allowOutsideClick: false,
                showCancelButton: true,
                cancelButtonText: "Cancel",
                reverseButtons: true,
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                    // todo: process of deleting categories
                }
            });
        });



    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>


@endsection