@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('barangs.index') }}" class="text-decoration-none">← Kembali ke List</a>
    <h3 class="mt-2">Tambah Barang Baru</h3>
</div>

<div class="card p-4">
    <form action="{{ route('barangs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">SKU (Barcode Content)</label>
                <input type="text" name="sku" class="form-control" placeholder="Contoh: BRG-001" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Stok Awal</label>
                <input type="number" name="stok" class="form-control" value="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" value="pcs" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga Beli (Modal)</label>
                <input type="number" name="harga_beli" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Foto Produk</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 2MB.</small>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary px-5">SIMPAN BARANG</button>
            </div>
        </div>
    </form>
</div>
@endsection