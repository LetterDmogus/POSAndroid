<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Barang;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('category')->latest();

        // Fitur Search (berdasarkan Nama atau SKU)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Fitur Filter by Kategori
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = Barang::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    public function show(string $id)
    {
        $barang = Barang::with('category')->find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $barang]);
    }

    // Custom method for QR Scan by SKU
    public function scan(string $sku)
    {
        $barang = Barang::with('category')->where('sku', $sku)->first();

        if (!$barang) {
            return response()->json(['message' => 'Barang dengan SKU ini tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $barang]);
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::find($id);
        if (!$barang) return response()->json(['message' => 'Barang tidak ditemukan'], 404);

        $barang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui',
            'data' => $barang
        ]);
    }

    public function destroy(string $id)
    {
        $barang = Barang::find($id);
        if (!$barang) return response()->json(['message' => 'Barang tidak ditemukan'], 404);

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}
