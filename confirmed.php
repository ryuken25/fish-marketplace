<?php
session_start();
include "./koneksi.php"; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['id_akun'])) {
    header("Location: ./login_ikan/login.php");
    exit();
}

// Check if the 'id_pesanan' parameter is set in the URL
if (!isset($_GET['id_pesanan'])) {
    header("Location: index.php"); // Redirect to the homepage if 'id_pesanan' is not provided
    exit();
}

$id_pesanan = $_GET['id_pesanan'];

// Fetch order details from the database
$selectOrderSql = "SELECT * FROM pesanan WHERE id_pesanan = ?";
$stmtOrder = $conn->prepare($selectOrderSql);
$stmtOrder->bind_param("i", $id_pesanan);
$stmtOrder->execute();
$resultOrder = $stmtOrder->get_result();

if ($resultOrder->num_rows > 0) {
    $orderDetails = $resultOrder->fetch_assoc();
} else {
    // Redirect to the homepage if order details are not found
    header("Location: index.php");
    exit();
}

// Fetch item details from the database
$selectItemsSql = "SELECT ikan.*, item_pesanan.quantity FROM item_pesanan
                   JOIN ikan ON item_pesanan.id_ikan = ikan.id_ikan
                   WHERE item_pesanan.id_pesanan = ?";
$stmtItems = $conn->prepare($selectItemsSql);
$stmtItems->bind_param("i", $id_pesanan);
$stmtItems->execute();
$resultItems = $stmtItems->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px auto;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        a {
            text-decoration: none;
            color: #3559E0;
            margin-bottom: 10px;
            display: block;
        }

        h2,
        h3 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #3559E0;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <a href="index.php">Home</a>

        <h3>Detail Pesanan</h3>
        <p><strong>Invoice ID:</strong> <?php echo $orderDetails['id_pesanan']; ?></p>
        <p><strong>Tanggal Pesan:</strong> <?php echo $orderDetails['order_date']; ?></p>
        <p><strong>Status:</strong> <?php echo $orderDetails['status']; ?></p>

        <h3>Item Yang Dipesan:</h3>
        <table>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php
            $totalAmount = 0;
            while ($item = $resultItems->fetch_assoc()) {
                $totalItemAmount = $item['quantity'] * $item['harga_ikan'];
                $totalAmount += $totalItemAmount;
            ?>
                <tr>
                    <td><?php echo $item['nama_ikan']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['harga_ikan']; ?></td>
                    <td><?php echo $totalItemAmount; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Total Amount:</strong></td>
                <td><?php echo $totalAmount; ?></td>
            </tr>
        </table>
    </div>

</body>

</html>

<?php
// Close prepared statements and database connection
$stmtOrder->close();
$stmtItems->close();
?>
