<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'barang_id',
        'jumlah',
        'tanggal_keluar'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}