@extends('app')

@section('content')

<br />
<br />

<div class="row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4>Log Barang</h4>
                            <div class="form-group">
                                <select class="form-select" name="act" id="act">
                                    <option>-- PILIH ACT LOG --</option>
                                    <option value="{{ route('log_barang.datatable', 'insert') }}">INSERT</option>
                                    <option value="{{ route('log_barang.datatable', 'update') }}">UPDATE</option>
                                    <option value="{{ route('log_barang.datatable', 'delete') }}">DELETE</option>
                                </select>
                            </div>
                        </div>
                        <table class="table table-striped" id="log_barang">
                            <thead>
                                <th>No</th>
                                <th>Agent</th>
                                <th>Act Date</th>
                                <th>Param</th>
                                <th>User</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    let _table = $('#log_barang');

    $(document).ready(function() {
        _table.DataTable({
            language: {
                "decimal": "",
                "emptyTable": "Data kosong",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(hasil dari _MAX_ total data)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Menampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ada data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": mengurutkan dari terkecil",
                    "sortDescending": ": mengurutkan dari terbesar"
                }
            },
            autoWidth: false,
            scrollX: true,
            processing: false,
            serverSide: false,
            order: [],
            ajax: {
                url: "{{ route('log_barang.datatable', 'insert') }}",
                type: "get",
            },
            lengthMenu: [
                [10, 25, 50, 100, 200],
                [10, 25, 50, 100, 200]
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            }, ],
            paging: true,
        });

        $('.dataTables_filter input').unbind();
        $('.dataTables_filter input').bind('keyup', function(e) {
            if (e.keyCode === 13) {
                table.search(this.value).draw();
            }
        })
    });

    $('#act').on('change', function() {
        let _url = $(this).val();
        
        _table.DataTable().destroy();

        _table.DataTable({
            language: {
                "decimal": "",
                "emptyTable": "Data kosong",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(hasil dari _MAX_ total data)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Menampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ada data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": mengurutkan dari terkecil",
                    "sortDescending": ": mengurutkan dari terbesar"
                }
            },
            autoWidth: false,
            scrollX: true,
            processing: false,
            serverSide: false,
            order: [],
            ajax: {
                url: _url,
                type: "get",
            },
            lengthMenu: [
                [10, 25, 50, 100, 200],
                [10, 25, 50, 100, 200]
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            }, ],
            paging: true,
        });

        $('.dataTables_filter input').unbind();
        $('.dataTables_filter input').bind('keyup', function(e) {
            if (e.keyCode === 13) {
                table.search(this.value).draw();
            }
        });
    })
</script>
@endpush

@endsection