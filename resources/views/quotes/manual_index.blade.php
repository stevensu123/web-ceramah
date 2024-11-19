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
                    <div class="d-flex ms-2 ">
                        <a class="btn btn-warning me-2" href="{{ route('quotes.index') }}" role="button">Back Tampilan Awal</a>
                        <a class="btn btn-primary" href="{{ route('quotes.manualCreate') }}" role="button">Tambah Quots</a>
                    </div>
                </div>
                <div class="col">
                    <!-- <div class="d-flex container-tmbh-data">
                        <a class="btn btn-warning me-2" href="{{ route('quotes.index') }}" role="button">Kembali Ke Tampilan Awal</a>
                        <a class="btn btn-primary" href="{{ route('quotes.manualCreate') }}" role="button">Tambah Kategori</a>
                    
                    </div> -->
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="main-container-wrapper">
                    <div class="table-wrapper">
                        <table class="table">
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
                                        <button type="button" class="btn btn-success expandable" data-id="{{ $quote->id }}">
                                            <span class="bx bx-search"></span>
                                        </button>
                                    </td>
                                    <td>{{ $quote->quote }}</td>
                                    <td>{{ $quote->categories->pluck('nama_kategori')->join(', ') }}</td>
                                    <td>
                                        <button class="btn btn-info btn-show" data-id="{{ $quote->id }}">Show</button>
                                        <br>
                                        <button class="btn btn-danger btn-delete" data-id="{{ $quote->id }}">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                        
                                    </td>
                                </tr>
                                <tr id="expandedRow-{{ $quote->id }}" class="expanded-row" style="">
                                    <td colspan="7">
                                        <div class="expanded-row__container">
                                            <div class="expanded-row__card left">
                                                <div class="expanded-row__main-text">Kutipan Translate</div>
                                                <div class="expanded-row__sub-text"> @if(!empty($quote->translated_quote))
                                                    {{ $quote->translated_quote }}
                                                    @else
                                                    Maaf, kutipan sudah berbahasa Indonesia, tidak ada terjemahan.
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="expanded-row__card left">
                                                <div class="expanded-row__main-text">Author</div>
                                                <div class="expanded-row__sub-text"><span class="badge bg-success me-1 span-author" >{{ $quote->author }}</span></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                <label for="">Quotes :</label>
                <p id="quotes-name" oninput="updateQuoteLength()" readonly></p>
                <h6 id="word-count" class="  count-kata">Jumlah Kata: 0</h6>
                <label for="">Quotes Translate :</label>
                <p id="quotes-translate" readonly></p>
                <p id=""> <span class="badge bg-label-success me-1" id="quotes-author"></span> </p>
                <span class="badge bg-label-primary me-1" id="quotes-kategori"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a class="btn btn-warning me-2" id="btn-edit" href="#" role="button">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-show').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                fetch('/quotes/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('quotes-name').textContent = (data.quote || 'Tidak ada Quots');
                        document.getElementById('quotes-translate').textContent = (data.quotes_translate || 'Tidak ada Translate');
                        document.getElementById('quotes-kategori').textContent = 'Quotes Kategori: ' + (data.nama_kategori || 'Tidak ada Kategori');
                        document.getElementById('quotes-author').textContent = 'Quotes Author: ' + (data.quotes_author || 'Tidak ada Author');

                        updateQuoteLength();
                        document.getElementById('btn-edit').href = '/quotes/edit/manual/' + id;
                        var modal = new bootstrap.Modal(document.getElementById('QuotesModal'));
                        modal.show();
                    });
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
    $(document).ready(function() {
        $('.expandable').on('click', function() {
            var quoteId = $(this).data('id');
            var expandedRow = $('#expandedRow-' + quoteId);
            expandedRow.toggleClass('expanded-row--open');
            
            // Toggle the visibility of the expanded row
            expandedRow.toggle();

            // Optional: Add active class to the button if needed
            $(this).toggleClass('btn-success btn-danger');
            $(this).toggleClass('active');
        });
    });


    function updateQuoteLength() {
        const paragraph = document.getElementById('quotes-name');
        const wordCountSpan = document.getElementById('word-count');

        // Mengambil teks dari elemen <p>
        const words = paragraph.textContent.trim().split(/\s+/).filter(function(n) {
            return n.length > 0;
        });
        const wordCount = words.length;
        wordCountSpan.textContent = `Jumlah Kata: ${wordCount}`;

        // Mengatur teks opsi berdasarkan jumlah kata
    }

    function autoGrow(element) {
        element.style.height = "auto"; /* Reset height */
        element.style.height = (element.scrollHeight) + "px"; /* Set height to scrollHeight */
    }
</script>

@endpush