@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Katalog Barang</h3>
    <a href="{{ route('barangs.create') }}" class="btn btn-primary">+ Tambah Barang Baru</a>
</div>

<div class="card p-4 mb-4">
    <form action="{{ route('barangs.index') }}" method="GET" class="row g-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau SKU..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary w-100">FILTER</button>
        </div>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Foto</th>
                    <th>SKU</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga Jual</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $barang)
                <tr>
                    <td class="ps-4">
                        @if($barang->foto)
                            <img src="{{ asset('storage/' . $barang->foto) }}" alt="foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">No Pic</div>
                        @endif
                    </td>
                    <td><code>{{ $barang->sku }}</code></td>
                    <td class="fw-bold">{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->category->nama_kategori }}</td>
                    <td>{{ $barang->stok }} {{ $barang->satuan }}</td>
                    <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-end pe-4">
                        <!-- Menggunakan SKU sebagai parameter -->
                        <a href="{{ route('barangs.edit', $barang->sku) }}" class="btn btn-sm btn-outline-info">Edit</a>
                        <form action="{{ route('barangs.destroy', $barang->sku) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $barangs->appends(request()->input())->links('pagination::bootstrap-5') }}
</div>
@endsection