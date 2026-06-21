<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        return BarangMasuk::with('barang')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date'
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        $barangMasuk = BarangMasuk::create([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk
        ]);

        $barang->stok += $request->jumlah;
        $barang->save();

        return response()->json([
            'message' => 'Barang masuk berhasil',
            'data' => $barangMasuk
        ]);
    }

    public function show(string $id)
    {
        return BarangMasuk::with('barang')->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Update barang masuk belum diaktifkan'
        ]);
    }

    public function destroy(string $id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);

        $barang = Barang::findOrFail($barangMasuk->barang_id);

        $barang->stok -= $barangMasuk->jumlah;
        $barang->save();

        $barangMasuk->delete();

        return response()->json([
            'message' => 'Data barang masuk dihapus'
        ]);
    }
}