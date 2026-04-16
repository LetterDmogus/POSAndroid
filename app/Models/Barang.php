<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'sku', 'nama_barang', 'deskripsi', 'harga_beli', 
        'harga_jual', 'stok', 'satuan', 'category_id', 'foto', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute()
    {
        return $this->foto ? url('storage/' . $this->foto) : null;
    }

    public function getRouteKeyName()
    {
        return 'sku';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
