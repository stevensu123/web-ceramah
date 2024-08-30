<!-- resources/views/quotes/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Vertical Layouts</h4>

        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Manual Create Quots Layout</h5>
                        <small class="text-muted float-end">Create</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('quotes.manualStore') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Author</label>
                                <input type="text" name="author" class="form-control"  placeholder="Default"  />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Kategori Emotional</label>
                                <select id="select1" name="category_id" onchange="showImageAndText(1)"
                                    class="form-select ">
                                    <option value="">Default select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Quotes / Kutipan Anda</label>
                                <h6 style="color: blue; font-size: 10px; overflow: hidden; resize: none;" for="">Tempat Memasukan Curhatan isi Hati Kamu</h6>
                                <textarea id="selected-text" name="quotes" oninput="updateQuoteLength(),autoGrow(this)" class="form-control textarea-noscroll"></textarea>
                                <h6 id="word-count"  class=" float-end count-kata">Jumlah Kata: 0</h6>
                                @error('text_cerita_sore')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori Panjang Kutipan</label>
                                <select id="quote_length" name="quote_length"
                                    class="form-select " disabled>
                                    <option value="short">Short (0-50 kata)</option>
                                    <option value="medium">Medium (51-100 kata)</option>
                                    <option value="long">Long (101+ kata)</option>
                                </select>

                            </div>

                            <br><br>

                            <button type="submit" name="manual" value="1">Insert Manual</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->
</div>

@endsection

@section ('javascript')
<script>
   function updateQuoteLength() {
    const textarea = document.getElementById('selected-text');
    const select = document.getElementById('quote_length');
    const wordCountSpan = document.getElementById('word-count');
    const words = textarea.value.trim().split(/\s+/).filter(function(n) { return n.length > 0 });
    const wordCount = words.length;
    wordCountSpan.textContent = `Jumlah Kata: ${wordCount}`;
    // Mengatur teks opsi berdasarkan jumlah kata
    if (wordCount <= 50) {
        select.value = 'short';
        select.options[0].text = `Short  `;
        select.options[1].text = 'Medium ';
        select.options[2].text = 'Long ';
    } else if (wordCount <= 100) {
        select.value = 'medium';
        select.options[0].text = 'Short ';
        select.options[1].text = `Medium `;
        select.options[2].text = 'Long ';
    } else {
        select.value = 'long';
        select.options[0].text = 'Short ';
        select.options[1].text = 'Medium ';
        select.options[2].text = `Long  `;
    }
}

    function autoGrow(element) {
        element.style.height = "auto"; /* Reset height */
        element.style.height = (element.scrollHeight) + "px"; /* Set height to scrollHeight */
    }
</script>
@endsection


