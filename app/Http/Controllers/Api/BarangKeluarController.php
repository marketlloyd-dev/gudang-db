<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        return BarangKeluar::with('barang')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date'
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok < $request->jumlah) {
            return response()->json([
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }

        $barangKeluar = BarangKeluar::create([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar
        ]);

        $barang->stok -= $request->jumlah;
        $barang->save();

        return response()->json([
            'message' => 'Barang keluar berhasil',
            'data' => $barangKeluar
        ]);
    }

    public function show(string $id)
    {
        return BarangKeluar::with('barang')->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Update barang keluar belum diaktifkan'
        ]);
    }

    public function destroy(string $id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        $barang = Barang::findOrFail($barangKeluar->barang_id);

        $barang->stok += $barangKeluar->jumlah;
        $barang->save();

        $barangKeluar->delete();

        return response()->json([
            'message' => 'Data barang keluar dihapus'
        ]);
    }
}