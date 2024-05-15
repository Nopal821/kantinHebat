<!-- di resources/views/tambah_makanan.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Makanan Baru</title>
</head>
<body>
    <h1>Tambah Makanan Baru</h1>
    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="nama">Nama Makanan:</label>
        <input type="text" id="nama" name="nama" required><br><br>
        <label for="harga">Harga:</label>
        <input type="text" id="harga" name="harga" required><br><br>
        <label for="gambar">Gambar:</label>
        <input type="file" id="gambar" name="gambar"><br><br>
        <button type="submit">Tambahkan</button>
    </form>
</body>
</html>
