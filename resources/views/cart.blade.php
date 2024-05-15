<!-- di resources/views/cart.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
</head>
<body>
    <h1>Keranjang Belanja Anda</h1>
    @if(count($cartItems) > 0)
        <ul>
            @foreach($cartItems as $item)
                <li>{{ $item['name'] }} - Jumlah: {{ $item['quantity'] }}</li>
            @endforeach
        </ul>
    @else
        <p>Keranjang Anda kosong.</p>
    @endif
    <a href="/">Kembali ke Daftar Makanan</a>
</body>
</html>
