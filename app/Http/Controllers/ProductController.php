<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    public function create()
    {
        return view('tambah_makanan');
    }
    
    public function addToCart(Request $request)
    {
        try {
            // Ambil product_id dan quantity dari request
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity');
    
            // Validasi apakah product_id dan quantity tidak kosong
            if (!$productId || !$quantity) {
                throw new \Exception("Product ID atau quantity tidak valid.");
            }
    
            // Ambil informasi produk dari database
            $product = Product::findOrFail($productId);
    
            // Validasi apakah produk ditemukan
            if (!$product) {
                throw new \Exception("Produk tidak ditemukan.");
            }
    
            // Simpan produk ke dalam session keranjang
            $cart = session()->get('keranjang') ?? [];
    
            // Jika produk sudah ada di keranjang, tambahkan jumlahnya
            if (isset($cart[$productId])) {
                $cart[$productId]['jumlah'] += $quantity;
            } else {
                // Jika produk belum ada di keranjang, tambahkan ke keranjang
                $cart[$productId] = [
                    'id' => $productId,
                    'nama' => $product->nama,
                    'harga' => $product->harga,
                    'jumlah' => $quantity,
                ];
            }
    
            // Simpan session keranjang setelah diperbarui
            session()->put('keranjang', $cart);
    
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            // Tangani pengecualian di sini
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function showCart()
    {
        $cartItems = session()->get('keranjang');

        return view('cart', compact('cartItems'));
    }

    
    // Fungsi untuk menyimpan data makanan baru
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,jpg|max:2048', // Pembatasan tipe dan ukuran file gambar
        ]);
    
        // Jika validasi gagal, kembalikan dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Coba simpan gambar ke penyimpanan yang sesuai (jika diperlukan)
        try {
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $namaFile = time() . '.' . $gambar->getClientOriginalExtension();
                $gambar->storeAs('public/images', $namaFile);
                $request['gambar'] = $namaFile;
            }
        } catch (\Exception $e) {
            // Jika terjadi kesalahan saat menyimpan gambar, log pesan kesalahan
            logger()->error('Error saving image: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan gambar. Error: ' . $e->getMessage());
        }
    
        // Coba simpan data ke dalam database
        try {
            Product::create($request->all());
        } catch (\Exception $e) {
            // Jika terjadi kesalahan saat menyimpan data, log pesan kesalahan
            logger()->error('Error saving data to database: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data. Error: ' . $e->getMessage());
        }
    
        // Jika berhasil, kembalikan ke halaman index dengan pesan sukses
        return redirect()->route('index')->with('success', 'Makanan berhasil ditambahkan.');
    }
}