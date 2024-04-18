<?php
$error = '';
if (!empty($_POST)) {
    $pdo = require 'koneksi.php';
    try {
        $pdo->beginTransaction();
        $queryKode = $pdo->prepare('SELECT count(*) as jml FROM ikan where kode=:kode1');
        $queryKode->execute(['kode1' => $_POST['kode']]);
        $count = $queryKode->fetchColumn();
        if ($count > 0) {
            throw new Exception('Kode ikan sudah digunakan masukkan yang lain');
        }

        $query = $pdo->prepare('INSERT INTO ikan
        (kode, nama, harga, stok, deskripsi) VALUES
        (:kode, :nama, :harga, :stok,:deskripsi)');
        $query->execute([
            'jenis_ikan' => $_POST['jenis_ikan'],
            'nama_ikan' => $_POST['nama_ikan'],
            'harga_ikan' => $_POST['harga_ikan'],
            'deskripsi' => $_POST['deskripsi'],
        ]);
        $ikanId = $pdo->lastInsertId();
        //proses gambar utama
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (isset($_FILES['gambar_ikan'])
            && $_FILES['gambar_ikan']['error'] == 0
            && $_FILES['gambar_ikan']['size'] > 0) {
            $tipeFile = $finfo->file($_FILES['gambar_ikan']['tmp_name']);
            if (!in_array($tipeFile, ['image/png','image/jpeg'])) {
                throw new Exception("Gambar tidak bisa diterima1");
            }

            $filename = md5(random_bytes(10)).'.'
                .pathinfo($_FILES['gambar_ikan']['name'], PATHINFO_EXTENSION);
            $queryGambarUtama = $pdo->prepare('INSERT INTO gambar_ikan
            (id_ikan, gambar, utama) VALUES (:id_ikan, :gambar, true)');
            $queryGambarUtama->execute([
                'id_ikan' => $ikanId,
                'gambar_ikan' => $filename
            ]);
            move_uploaded_file($_FILES['gambar_ikan']['tmp_name'], '../images/'.$filename);
        }
        foreach($_FILES['gambar']['name'] as $index => $name) {
            if (empty($name)) {
                continue;
            }
            if ($_FILES['gambar']['error'][$index] != 0 || $_FILES['gambar']['size'][$index] <= 0) {
                // echo '<pre>';
                // print_r($_FILES['gambar']);
                throw new Exception('gambar tidak bisa diupload');
            }

            $tipeFile = $finfo->file($_FILES['gambar']['tmp_name'][$index]);
            if (!in_array($tipeFile, ['image/png', 'image/jpeg'])) {
                throw new Exception('Gambar tidak bisa diterima2');
            }

            $filename = md5(random_bytes(10)).'.'
            .pathinfo($name, PATHINFO_EXTENSION);
            $queryGambar = $pdo->prepare('INSERT INTO gambar_ikan
            (id_ikan, gambar_ikan, utama) VALUES (:id_ikan, :gambar_ikan, false)');
            $queryGambar->execute([
                'id_ikan' => $produkId,
                'gambar' => $filename
            ]);

            move_uploaded_file($_FILES['gambar_ikan']['tmp_name'][$index], '../ikan/'.$filename);
        }
        $pdo->commit();
        header("Location: tambah-produk.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Tambah Produk</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form action="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3>
                        <label for="> Gambar Utama</label>
                        <input type="file" name="gambar_ikan" required
                        accept="image/png, image/jpg" class="form-control">
                    </div>
                    <div class="mb-3>
                        <label for=">Jenis</label>
                        <input type="text" name="kode" class="form-control" required
                        value="<?php echo $_POST['jenis_ikan'] ?? ""; ?>">
                    </div>
                    <div class="mb-3>
                        <label for=">Nama</label>
                        <input type="text" name="nama" class="form-control" required
                        value="<?php echo $_POST['nama_ikan'] ?? ""; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Harga</label>
                        <input type="number" name="harga" class="form-control" required
                        value="<?php echo $_POST['harga_ikan'] ?? ""; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control"
                        value="<?php echo $_POST['deskripsi'] ?? ""; ?>"></textarea> 
                    </div>
                    <button type="submit">Simpan</button>
                </form>

            </div>
        </div>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</html>