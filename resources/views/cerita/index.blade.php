@extends('layouts.app')

@section ('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Cerita</h4>
        <!-- <hr class="my-5" /> -->
        <!-- Bordered Table -->

        <!--/ Bordered Table -->
        <div class="card " style="margin-top: 30px;">
            <div style="margin:20px" id='calendar'></div>
        </div>

    </div>

</div>

@endsection

@section('javascript')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            // validRange: {
            //     start: new Date().toISOString().split('T')[0] // Mengatur tanggal mulai valid ke tanggal hari ini
            // },
            dateClick: function(info) {
                window.location.href = '/cerita/date/' + info.dateStr;
            }
        });
        calendar.render();
    });
   
</script>
@endsection