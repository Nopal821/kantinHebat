<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja Anda</title>
</head>
<body>
    <h1>Keranjang Belanja Anda</h1>

    @if(count($cart_products) > 0)
        <ul>
            @foreach($cart_products as $key => $product)
                <li>
                    Produk: {{ $product['name'] }} <br>
                    Harga: Rp. {{ $product['price'] }} <br>
                    Jumlah: {{ $product['quantity'] }} <br>
                    Total: Rp. {{ $product['quantity'] * $product['price'] }} <!-- Menggunakan 'price' bukan 'discount_price' -->
                </li>
            @endforeach
        </ul>
        <p>Total Keranjang: Rp. {{ $cart_total }}</p>
        <form action="{{ route('checkout') }}" method="get">
            <button type="submit">Pembayaran</button>
        </form>
    @else
        <p>Keranjang Anda kosong.</p>
    @endif

    <a href="{{ route('index') }}">Kembali ke Daftar Makanan</a>
</body>
</html>
