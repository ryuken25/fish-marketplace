<?php
include "./koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];

$query_email = mysqli_query($conn, "SELECT email FROM akun WHERE email='$email'");

$cek = mysqli_num_rows($query_email);

if ($cek != 0) {
    echo "
    <script>
    alert('email sudah ada, silahkan masukan email yang lain');
    window.location.href='register.php';
    </script>
    ";
} else {
    $result = mysqli_query($conn, "INSERT INTO akun (nama, email, password, alamat) VALUES ('$nama','$email','$password', '$alamat')");

    if ($result) {
        // Retrieve the last inserted ID
        $lastInsertID = mysqli_insert_id($conn);

        // Set id_keranjang to be the same as id_akun
        $updateQuery = "UPDATE akun SET id_keranjang = $lastInsertID WHERE id_akun = $lastInsertID";
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            echo "Failed to update id_keranjang: " . mysqli_error($conn);
        } else {
            header('location: ./login_ikan/login.php');
            exit;
        }
    } else {
        echo "Failed to register: " . mysqli_error($conn);
    }
}
?>
