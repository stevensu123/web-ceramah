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
                        <a class="btn btn-primary" href="{{route('heroimage.create')}}" role="button">Tambah Kategori</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Kategori</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategori as $kt)
                            <tr>
                                <td>
                                    @php $path = Storage::url('upload/emotikon/'.$kt->gambar); @endphp
                                    <img src="{{url($path)}}" alt="" class="img-fluid table-img">
                                </td>
                                <td>{{$kt->nama_kategori}}</td>
                                <td><span class="badge bg-label-primary me-1">{{ $kt->status ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                                <td>
                                    <button class="btn btn-info btn-show" data-id="{{ $kt->id }}">Show</button>
                                    <br>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $kt->id }}"><i class="bx bx-trash me-1"></i> Delete</button>

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
 
</script>
@endsection