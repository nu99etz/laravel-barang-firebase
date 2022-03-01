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
                            <h4>Data Barang</h4>
                        </div>
                        @if(Auth::user())
                        <div style="float: right;">
                            <button style="float: right;" action="{{ route('barang.create') }}" type="button" class="btn btn-sm btn-success btn-flat btn-add"><i class="fa fa-plus"></i> Tambah Produk</button>
                        </div>
                        @endif
                        <br />
                        <br />
                        <table class="table table-striped" id="barang" url="{{ route('barang.datatable') }}">
                            <thead>
                                <th data-column="DT_RowIndex" data-searchable="false" data-orderable="false">No</th>
                                <th data-column="kode_barang" data-searchable="true" data-orderable="true">Kode Barang</th>
                                <th data-column="nama_barang" data-searchable="true" data-orderable="true">Nama Barang</th>
                                <th data-column="stok" data-searchable="true" data-orderable="true">Stok Barang</th>
                                <th data-column="harga" data-searchable="true" data-orderable="true">Harga Barang</th>
                                <th data-column="action" data-searchable="false" data-orderable="false">Aksi</th>
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

@php
$modal_id = "modal_barang";
$modal_size = "sm";
$modal_title = "Form Barang";
@endphp

@include('modal')

@push('script')
<script>
    let _table = $('#barang');
    let _modal = $('#modal_barang');

    $(document).ready(function() {
        DataTables(_table);
    });

    $(document).on('click', '.btn-add', function() {
        let _url = $(this).attr('action');
        getViewModal(_url, _modal);
    });

    $(document).on('click', '.update', function() {
        let _url = $(this).attr('action');
        getViewModal(_url, _modal);
    });

    $(document).on('click', '.read', function() {
        let _url = $(this).attr('action');
        getViewModal(_url, _modal);
    });

    $(document).on('submit', 'form#barang_form', function() {
        event.preventDefault();
        let _data = new FormData($(this)[0]);
        let _url = $(this).attr('action');
        send((data, xhr = null) => {
            if (data.status == 200) {
                SuccessNotif(data.messages);
                _modal.modal('hide');
                _table.DataTable().ajax.reload();
            } else if (data.status == 422) {
                FailedNotif(data.messages);
            }
        }, _url, 'json', 'post', _data);
    });

    $(document).on('click', '.delete', function() {
        let _url = $(this).attr('action');
        Swal.fire({
            title: 'Apakah Anda Yakin Menghapus Data Ini ?',
            showCancelButton: true,
            confirmButtonText: `Hapus`,
            confirmButtonColor: '#d33',
            icon: 'question'
        }).then((result) => {
            if (result.value) {
                send((data, xhr = null) => {
                    if (data.status == 200) {
                        Swal.fire("Sukses", data.messages, 'success');
                        _table.DataTable().ajax.reload();
                    }
                }, _url, "json", "delete");
            }
        });
    });
</script>
@endpush

@endsection