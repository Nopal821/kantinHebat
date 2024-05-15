<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Online</title>
</head>
<body>
    <h1>Selamat Datang di Kantin Online</h1>
    <h2>Daftar Makanan</h2>
    <ul>
        @foreach($products as $product)
            <li>
                <img src="{{ asset('storage/images/' . $product->gambar) }}" alt="{{ $product->nama }}" style="max-width: 100px; max-height: 100px;">
                <div>
                    <h3>{{ $product->nama }}</h3>
                    <p>Rp. {{ number_format($product->harga, 0, ',', '.') }}</p>
                </div>
                <form action="{{ route('cart.add') }}" method="post">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1">
                    <button type="submit">Tambah ke Keranjang</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
