<form id="barang_form" method="post" action="{{ $action }}" enctype="multipart/form-data">

    @if(!empty($barang) && empty($readonly))
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="{{ $barang->id }}">
    <input type="hidden" name="kode_barang_edit" value="{{ $barang->kode_barang }}">
    @endif

    <div class="mb-3">
        <div class="form-group">
            <label for="kode_barang" class="form-label">Kode Barang</label>
            <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="{{ $barang->kode_barang ?? '' }}" {{ $readonly ?? '' }}>
        </div>
    </div>
    <div class="mb-3">
        <div class="form-group">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" value="{{ $barang->nama_barang ?? '' }}" {{ $readonly ?? '' }}>
        </div>
    </div>
    <div class="mb-3">
        <div class="form-group">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" name="stok" id="stok" placeholder="Stok" value="{{ $barang->stok ?? '' }}" {{ $readonly ?? '' }}>
        </div>
    </div>
    <div class="mb-3">
        <div class="form-group">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" value="{{ $barang->harga ?? '' }}" {{ $readonly ?? '' }}>
        </div>
    </div>
    @if(empty($readonly))
    <div class="mb-3">
        <div class="form-group">
            <button type="submit" class="btn btn-primary mb-3">Simpan</button>
            <button type="reset" class="btn btn-warning mb-3">Reset</button>
        </div>
    </div>
    @endif
</form>