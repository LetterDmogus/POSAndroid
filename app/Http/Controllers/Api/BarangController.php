<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('category')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $barangs = $query->get();

        return response()->json([
            'success' => true,
            'count' => $barangs->count(),
            'data' => $barangs
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer',
            'satuan' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('produk', 'public');
            $data['foto'] = $path;
        }

        $barang = Barang::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang->load('category')
        ], 201);
    }

    public function show(string $id)
    {
        $barang = Barang::with('category')->find($id);
        if (!$barang) return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $barang]);
    }

    public function scan(string $sku)
    {
        $barang = Barang::with('category')->where('sku', $sku)->first();
        if (!$barang) return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $barang]);
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::find($id);
        if (!$barang) return response()->json(['message' => 'Barang tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:barangs,sku,' . $id,
            'nama_barang' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer',
            'satuan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($barang->foto) Storage::disk('public')->delete($barang->foto);
            $path = $request->file('foto')->store('produk', 'public');
            $data['foto'] = $path;
        }

        $barang->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui',
            'data' => $barang->load('category')
        ]);
    }

    public function destroy(string $id)
    {
        $barang = Barang::find($id);
        if (!$barang) return response()->json(['message' => 'Barang tidak ditemukan'], 404);

        if ($barang->foto) Storage::disk('public')->delete($barang->foto);
        $barang->delete();

        return response()->json(['success' => true, 'message' => 'Barang dihapus']);
    }
}