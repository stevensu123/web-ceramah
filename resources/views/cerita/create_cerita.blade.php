@extends('layouts.app')

@section ('content')
<div class="card">
    <div class="row">
        <div class="col">
            <div class="card-header">
                <h2 class="tmbh-data-title">Bordered Table</h2>
            </div>

        </div>
        <div class="col">
            <div class="d-flex container-tmbh-data">
                <a class="btn btn-primary" href="{{route('cerita.create')}}" role="button">Tambah Kategori</a>
            </div>
        </div>
    </div>
</div>
@endsection