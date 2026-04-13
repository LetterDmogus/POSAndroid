<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'sku', 'nama_barang', 'deskripsi', 'harga_beli', 
        'harga_jual', 'stok', 'satuan', 'category_id', 'foto', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
