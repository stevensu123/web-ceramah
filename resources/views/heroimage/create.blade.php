@extends('layouts.app')

@section ('content')
<style>
    /* Gaya untuk gambar yang dapat dipilih */
    .selectable-image {
        width: 100px;
        /* Sesuaikan dengan kebutuhan */
        margin: 5px;
        cursor: pointer;
    }
</style>

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
                        <form action="{{ route('heroimage.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Pilih Background Image -->
                            <div class="mb-3">
                            <div class="form-group">
    <button type="button" id="choose-background-btn">Choose File Background</button>
    <input type="hidden" id="background_input">
    <div id="background_preview"></div>
</div>

<div class="mb-3">
    <div class="form-group">
        <button type="button" id="choose-author-btn">Choose File Author</button>
        <input type="hidden" id="author_input">
        <div id="author_preview"></div>
    </div>
</div>

                            <!-- Tombol Submit -->
                            <button type="submit" class="btn btn-success">Upload Kutipan dan Gambar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')

<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script>
     document.getElementById('choose-background-btn').addEventListener('click', function() {
        // URL untuk membuka Filemanager dengan folder background
        var lfmUrl = '/laravel-filemanager?type=image&folder=background';
        window.open(lfmUrl, 'Filemanager', 'width=900,height=600');
    });

    document.getElementById('choose-author-btn').addEventListener('click', function() {
        // URL untuk membuka Filemanager dengan folder author
        var lfmUrl = '/laravel-filemanager?type=image&folder=author';
        window.open(lfmUrl, 'Filemanager', 'width=900,height=600');
    });
</script>

@endpush
