<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangWebController extends Controller
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

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $barangs = $query->paginate(10);
        $categories = Category::all();

        return view('admin.barangs.index', compact('barangs', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.barangs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer',
            'satuan' => 'required',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('produk', 'public');
            $data['foto'] = $path;
        }

        Barang::create($data);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $categories = Category::all();
        return view('admin.barangs.edit', compact('barang', 'categories'));
    }

    public function update(Request $request, Barang $barang)
    {
        // Debugging: Jika kamu ingin melihat data yang masuk, hapus komentar di bawah:
        // dd($request->all());

        $data = $request->validate([
            'sku' => 'required|unique:barangs,sku,' . $barang->id,
            'nama_barang' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer',
            'satuan' => 'required',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }
            $path = $request->file('foto')->store('produk', 'public');
            $data['foto'] = $path;
        } else {
            unset($data['foto']);
        }

        $barang->update($data);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
        }
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus');
    }
}