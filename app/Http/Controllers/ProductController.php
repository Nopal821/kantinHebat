<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Exception;

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
        $this->validateRequest($request);

        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity');

            $product = $this->getProduct($productId);
            $cart = $this->getCart();

            if ($this->productAlreadyInCart($cart, $productId)) {
                $this->updateCartQuantity($cart, $productId, $quantity);
            } else {
                $this->addProductToCart($cart, $product, $quantity);
            }

            Session::put('keranjang', $cart);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (Exception $e) {
            Log::error('Error adding product to cart: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showCart()
    {
        $cartItems = Session::get('keranjang');

        return view('cart', compact('cartItems'));
    }

    public function store(Request $request)
    {
        // ...
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