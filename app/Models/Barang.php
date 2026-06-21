<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kategori_id',
        'kode_barang',
        'nama_barang',
        'stok',
        'harga_beli',
        'harga_jual'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}