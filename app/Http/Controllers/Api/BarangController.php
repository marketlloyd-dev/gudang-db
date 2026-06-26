<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json(
            Barang::with('kategori')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'kode_barang' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'stok' => 'required|integer',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric'
        ]);

        $barang = \DB::transaction(function () use ($request) {
            $barang = \App\Models\Barang::create([
                'kategori_id' => $request->kategori_id,
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'stok' => $request->stok,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual
            ]);

            if ($barang->stok > 0) {
                \App\Models\BarangMasuk::create([
                    'barang_id' => $barang->id,
                    'jumlah' => $barang->stok,
                    'tanggal_masuk' => now()->toDateString()
                ]);
            }

            return $barang;
        });

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    public function show(string $id)
    {
        $barang = Barang::with('kategori')->find($id);

        if (!$barang) {
            return response()->json([
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json($barang);
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $barang->update($request->all());

        return response()->json([
            'message' => 'Barang berhasil diupdate',
            'data' => $barang
        ]);
    }

    public function destroy(string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}