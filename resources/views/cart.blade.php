<!-- di resources/views/cart.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja Anda</title>
</head>
<body>
    <h1>Keranjang Belanja Anda</h1>
    <form action="{{ route('cart.update') }}" method="post">
        @csrf
        @if(session('cartItems'))
            <ul>
                @foreach(session('cartItems') as $id => $item)
                    <li>{{ $item['nama'] }} - Rp. {{ $item['harga'] }} - Jumlah: 
                        <input type="number" name="jumlah[{{ $id }}]" value="{{ $item['jumlah'] }}">
                        <input type="hidden" name="id_barang" value="{{ $id }}">
                        <button type="submit" name="kurangi_barang">Kurangi</button>
                        <button type="submit" name="hapus_barang">Hapus</button>
                    </li>
                @endforeach
            </ul>
            <button type="submit" name="update_keranjang">Update Keranjang</button>
            <a href="{{ route('checkout') }}">Pembayaran</a>
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
    </form>
    <a href="{{ route('index') }}">Kembali ke Daftar Makanan</a>
</body>
</html>
