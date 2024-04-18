<?php
session_start();
include "./koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jualan Ikan Admin</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .admin-button {
            background-color: #1E3859;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
        }   

        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .container-page {
            width: 100%;
            text-align: left;
        }

        h1 {
            color: #3559E0;
            font-size: 2.5em;
        }

        p {
            color: #4CB9E7;
            font-size: 1.5em;
        }

        button {
            background-color: #96B6C5;
            color: white;
            font-size: 1.5em;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .m-50 {
            width: 90%;
        }

        .w-10 {
            width: 90%;
            border-radius: 12px;
        }
        .footer {
            background-color: #0F2167;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    include "navbar-admin.php";
    ?>
    <div class="flex">
        <div class="container-page">
            <h1>Welcome To Admin Page</h1>
        <button onclick="redirectToCRUD()">Product Settings</button>
        <button onclick="redirectToInvoice()">Invoice</button>
        <button onclick="redirectToPendapatan()">Pendapatan</button>
        </div>
        <div class="m-50">
            <img class="m-30 w-10" src="./ikan_image/logo.png" alt="">
        </div>
    </div>
    <?php
    include "./product.php";
    ?>
   <div class="footer">
        &copy; Pemuda FPFisher
    </div>
    <script>
    function redirectToCRUD() {

        window.location.href = './admin/read-product.php';
    }
    function redirectToInvoice() {

        window.location.href = './admin/pesanan-admin.php';
    }
    function redirectToPendapatan() {

window.location.href = 'pendapatan.php';
}
</script>
</body>

</html>
