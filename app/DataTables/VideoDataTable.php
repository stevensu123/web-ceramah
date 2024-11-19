<?php

namespace App\DataTables;

use App\Models\Video;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class VideoDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    // public function dataTable(QueryBuilder $query): EloquentDataTable
    // {
    //     return datatables()
    //     ->of($query)
    //     ->addColumn('action', function ($row) {
    //         return '
    //             <a href="javascript:void(0)" class="edit btn btn-primary btn-lg" onclick="showVideoDetails(' . $row->id . ')">View</a>
    //             <a href="' . route('videos.edit', $row->id) . '" class="edit btn btn-warning me-2 btn-lg">Edit</a>
    //             <button class="btn btn-danger btn-lg btn-delete" data-id="' . $row->id . '" data-title="' . htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') . '">Delete</button>
    //         ';
    //     })
    //     ->editColumn('created_at', function ($row) {
    //         return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y'); // Format tanggal
    //     })
    //     ->editColumn('video_path', function ($row) {
    //         // Menghilangkan tanda garis bawah dan memotong jika terlalu panjang
    //         $formattedVideoPath = str_replace('_', ' ', $row->video_path);
    //         return \Illuminate\Support\Str::limit($formattedVideoPath, 50, '...'); // Memotong jika terlalu panjang
    //     })
    //     ->rawColumns(['action', 'video_path']);
    // }
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return datatables()
        ->of($query)
        ->addColumn('action', function ($row) {
            // Log nilai create_by
            Log::info('create_by: ' . $row->create_by);

            $editUrl = $row->create_by === 'file_upload' 
                ? route('videos.edit.from.file', $row->id) 
                : route('videos.edit.from.youtube', $row->id);

            return '
                <a href="javascript:void(0)" class="edit btn btn-primary btn-lg" onclick="showVideoDetails(' . $row->id . ')">View</a>
                <a href="' . $editUrl . '" class="edit btn btn-warning me-2 btn-lg">Edit</a>
                <button class="btn btn-danger btn-lg btn-delete" data-id="' . $row->id . '" data-title="' . htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') . '">Delete</button>
            ';
        })
        ->editColumn('created_at', function ($row) {
            return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
        })
        ->editColumn('video_path', function ($row) {
            return \Illuminate\Support\Str::limit(str_replace('_', ' ', $row->video_path), 50, '...');
        })
        ->rawColumns(['action', 'video_path']);
}

    /**
     * Get the query source of dataTable.
     */
    public function query(Video $model): QueryBuilder
    {
        return $model->newQuery()->select(['id', 'title', 'description', 'video_path', 'created_at', 'create_by']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
   public function html(): HtmlBuilder
{
    return $this->builder()
        ->setTableId('videos-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->dom('Blfrtip') // Mengaktifkan tombol lainnya dan dropdown length
        ->orderBy(1)
        ->addTableClass('table table-striped table-bordered')
        ->language([
            'emptyTable' => 'Tidak ada data video',
        ])
        ->parameters([
            'lengthMenu' => [[5, 10, 15, 100, 500], [5, 10, 15, 100, 500]], // Menentukan opsi jumlah data per halaman
            'pageLength' => 5, // Jumlah data default yang ditampilkan pada awalnya
            'initComplete' => "function(settings, json) {
                // Menyembunyikan elemen asli dropdown length dari DataTables
                $('.dataTables_length').hide();
                
                // Menggunakan dropdown kustom yang telah Anda buat
                const lengthMenu = $('.dataTables_length select');
                const dropdown = $('.select-menu');
                const selectBtn = dropdown.find('.select-btn');

                // Event listener untuk membuka dropdown custom
                selectBtn.on('click', function() {
                    dropdown.toggleClass('active');
                });

                // Event listener untuk item dropdown custom
                dropdown.find('.option').on('click', function() {
                    const selectedValue = $(this).data('value');
                    lengthMenu.val(selectedValue).trigger('change');
                    dropdown.removeClass('active');
                    selectBtn.find('.sBtn-text').text($(this).find('.option-text').text());
                });

                // Menutup dropdown ketika mengklik di luar elemen dropdown
                $(document).on('click', function(e) {
                    if (!dropdown.is(e.target) && dropdown.has(e.target).length === 0) {
                        dropdown.removeClass('active');
                    }
                });
            }"
        ]);
}


    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'video_path', 'name' => 'video_path', 'title' => 'Video Path'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Video_' . date('YmdHis');
    }
}
