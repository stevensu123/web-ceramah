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
                        <h2 class="tmbh-data-title">View Quots</h2>
                    </div>

                </div>
                <div class="col">
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <p>NB: Tambahkan quots secara manual dengan mengklik tombol "Tambah Quots Manual".</p>
                <a class="btn btn-warning me-2" href="{{ route('quotes.manual') }}" role="button">Tambah Quots Manual</a>

                <!-- Garis Pemisah -->
                <hr class="my-3">

                <!-- Teks Deskripsi untuk Tombol "Tambah Quots Auto Generate" -->
                <p>NB: Tambahkan quots secara otomatis dengan mengklik tombol "Tambah Quots Auto Generate".</p>
                <a class="btn btn-primary" href="{{ route('quotes.auto') }}" role="button">Tambah Quots Auto Generate</a>
            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>


@endsection

@section('javascript')


@endsection