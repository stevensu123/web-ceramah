@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Kategori</h4>
        <!-- Bordered Table -->
        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <h2 class="tmbh-data-title">Bordered Table</h2>
                    </div>
                </div>
                <div class="col">

                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="d-flex container-tmbh-data">
                    <a class="btn btn-primary" href="{{ route('quotes.manual') }}" role="button">Tambah Quots Manual</a>
                </div>
                <div class="d-flex container-tmbh-data">
                    <a class="btn btn-primary" href="{{ route('quotes.auto') }}" role="button">Tambah Quots Auto Generate</a>
                </div>
            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>
@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-show').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                fetch('/quotes/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('quotes-name').textContent = 'Quotes: ' + (data.quote || 'N/A');
                        document.getElementById('quotes-translate').textContent = 'Quotes Translate: ' + (data.quotes_translate || 'N/A');
                        document.getElementById('quotes-kategori').textContent = 'Quotes Kategori: ' + (data.nama_kategori || 'N/A');
                        document.getElementById('quotes-author').textContent = 'Quotes Author: ' + (data.quotes_author || 'N/A');
                        var modal = new bootstrap.Modal(document.getElementById('QuotesModal'));
                        modal.show();
                    });
            });
        });

        document.querySelectorAll('.exploder').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                this.classList.toggle("btn-success");
                this.classList.toggle("btn-danger");
                this.children[0].classList.toggle("bx-search");
                this.children[0].classList.toggle("bx-zoom-out");
                var detailsRow = document.getElementById('details-' + id);
                if (detailsRow.style.display === "none") {
                    detailsRow.style.display = "";
                } else {
                    detailsRow.style.display = "none";
                }
            });
        });

        document.querySelectorAll('.btn-delete').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');

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
                        fetch('/quotes/' + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => {
                            if (response.ok) {
                                Swal.fire(
                                    'Terhapus!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
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
        });
    });

    function toggleQuote(element) {
        var container = element.parentNode.parentNode.querySelector('.quote-container');
        var shortQuote = container.querySelector('.short-quote');
        var moreText = container.querySelector('.more-text');
        var moreLink = container.querySelector('.toggle-more');
        var lessLink = container.querySelector('.toggle-less');

        if (container.classList.contains('expanded')) {
            container.classList.remove('expanded');
            moreText.style.display = "none";
            moreLink.style.display = "inline";
            lessLink.style.display = "none";
        } else {
            container.classList.add('expanded');
            moreText.style.display = "block";
            moreLink.style.display = "none";
            lessLink.style.display = "inline";
        }
    }
</script>

@endsection