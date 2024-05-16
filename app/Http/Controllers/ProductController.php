<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Exception; // Update this import

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
        // Validasi request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
    
        // Ambil informasi produk dari database
        $product = Product::findOrFail($productId);
    
        // Simpan produk ke dalam session keranjang
        $cart = session()->get('cart', []);
    
        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Jika produk belum ada di keranjang, tambahkan ke keranjang
            $cart[$productId] = [
                'id' => $productId,
                'name' => $product->nama, // Tambahkan kunci 'name' dengan nilai nama produk
                'price' => $product->harga, // Tambahkan kunci 'price' dengan nilai harga produk
                'quantity' => $quantity,
            ];
        }
    
        session()->put('cart', $cart);
    
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
    
    
    public function updateCart(Request $request)
    {
        // Implement the logic to update the cart based on the submitted form data
    }
    
    public function showCart()
    {
        $cart_products = collect(request()->session()->get('cart'));
    
        $cart_total = 0;
        if(session('cart')){
            foreach ($cart_products as $key => $product) {
                $cart_total+= $product['quantity'] * $product['price']; // Menggunakan 'harga' tanpa diskon
            }
        }
    
        return view('cart', compact('cart_products', 'cart_total'));
    }
    

    

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException('Invalid request data');
        }
    }

    private function getProduct($productId)
    {
        return Product::findOrFail($productId);
    }

    private function getCart()
    {
        return Session::get('keranjang', []);
    }

    private function productAlreadyInCart($cart, $productId)
    {
        return isset($cart[$productId]);
    }

    private function updateCartQuantity($cart, $productId, $quantity)
    {
        $cart[$productId]['jumlah'] += $quantity;
    }

    private function addProductToCart($cart, $product, $quantity)
    {
        $cart[$product->id] = [
            'id' => $product->id,
            'nama' => $product->nama,
            'harga' => $product->harga,
            'jumlah' => $quantity,
        ];
    }
}

