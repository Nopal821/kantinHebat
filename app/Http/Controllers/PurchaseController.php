<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product; // tambahkan model Product

class PurchaseController extends Controller
{
    public function checkout(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        // Ambil data dari form
        $nama = $validatedData['nama'];
        $alamat = $validatedData['alamat'];

        // Hitung total harga dari keranjang
        $total = $this->hitungTotalKeranjang();

        // Simpan pembelian ke dalam database
        $pembelian = Purchase::create([
            'nama' => $nama,
            'alamat' => $alamat,
            'total' => $total,
        ]);

        // Hapus keranjang setelah checkout
        session()->forget('keranjang');

        return redirect('/')->with('success', 'Pembelian berhasil.');
    }

    // Fungsi untuk menghitung total harga keranjang
    private function hitungTotalKeranjang()
    {
        $total = 0;

        if(session()->has('keranjang')) {
            foreach(session('keranjang') as $produk) {
                $total += $produk['harga'] * $produk['jumlah'];
            }
        }

        return $total;
    }
}
