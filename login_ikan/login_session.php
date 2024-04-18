<?php
session_set_cookie_params(1000000, '/');
session_start();
include "../koneksi.php";

if (isset($_POST['email']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Email dan password diperlukan");
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM akun WHERE email=? AND password=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);

        // Bind the result variables
        mysqli_stmt_bind_result($stmt, $id, $nama, $id_keranjang, $email, $password, $alamat, $isAdmin);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

        // Check if a row was fetched
        if ($id) {
            $_SESSION['email'] = $email;
            $_SESSION['nama'] = $nama;
            $_SESSION['id_akun'] = $id;
            $_SESSION['isAdmin'] = $isAdmin;

            if ($isAdmin == 1) {
                // Redirect to admin.php if user is an admin
                header("Location: ../admin.php");
            } else {
                // Redirect to index.php for non-admin users
                header("Location: ../index.php");
            }
            exit();
        } else {
            header("Location: login.php?error=Email atau password salah");
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in preparing statement: " . mysqli_error($conn);
    }
} else {
    header("Location: login.php");
    exit();
}
?>
