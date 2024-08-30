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
                        <h2 class="tmbh-data-title">Table Quots Manual</h2>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex container-tmbh-data">
                    <a class="btn btn-warning" href="{{ route('quotes.index') }}" role="button">Back Tampilan Awal</a>
                        <a class="btn btn-primary" href="{{ route('quotes.manualCreate') }}" role="button">Tambah Kategori</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Show Detail</th>
                                <th>Kutipan</th>
                                <th>Kategori</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotes as $quote)
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-success exploder" data-id="{{ $quote->id }}">
                                        <span class="bx bx-search"></span>
                                    </button>
                                </td>
                                <td>
                                    <div class="quote-container">
                                        <span class="short-quote">{{ Str::limit($quote->quote, 100) }}</span>
                                        @if (strlen($quote->quote) > 100)
                                        <span class="more-text" style="display: none;">{{ substr($quote->quote, 100) }}</span>
                                        <a href="javascript:void(0);" class="toggle-more" onclick="toggleQuote(this)">More</a>
                                        <a href="javascript:void(0);" class="toggle-less" style="display: none;" onclick="toggleQuote(this)">Less</a>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $quote->categories->pluck('nama_kategori')->join(', ') }}</td>
                                <td>
                                    <button class="btn btn-info btn-show" data-id="{{ $quote->id }}">Show</button>
                                    <br>
                                    <button class="btn btn-danger btn-delete" data-id="{{ $quote->id }}"><i class="bx bx-trash me-1"></i> Delete</button>
                                </td>
                            </tr>
                            <tr class="explode hide" id="details-{{ $quote->id }}" style="display: none;">
                                <td colspan="4" style="background: #CCC;">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Translate Kutipan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $quote->translated_quote }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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

<!-- Modal untuk Detail Kategori -->
<div class="modal fade" id="QuotesModal" tabindex="-1" aria-labelledby="QuotesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="QuotesModalLabel">QuotesDetails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="quotes-name"></p>
                <p id="quotes-translate"></p>
                <p id=""> <span class="badge bg-label-success me-1" id="quotes-author"></span> </p>
                <span class="badge bg-label-primary me-1" id="quotes-kategori"></span>
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