@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('barangs.index') }}" class="text-decoration-none">← Kembali ke List</a>
    <h3 class="mt-2">Edit Barang: {{ $barang->nama_barang }}</h3>
</div>

<div class="card p-4">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Menggunakan POST langsung ke route update (kita akan sesuaikan di web.php) -->
    <form action="{{ route('barangs.update_web', $barang->sku) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">SKU (Barcode Content)</label>
                <input type="text" name="sku" class="form-control" value="{{ $barang->sku }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $barang->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ $barang->stok }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" value="{{ $barang->satuan }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga Beli (Modal)</label>
                <input type="number" name="harga_beli" class="form-control" value="{{ (int)$barang->harga_beli }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" value="{{ (int)$barang->harga_jual }}" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Foto Produk</label>
                @if($barang->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $barang->foto) }}" alt="current" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ $barang->deskripsi }}</textarea>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-info text-white px-5">UPDATE DATA</button>
            </div>
        </div>
    </form>
</div>
@endsection