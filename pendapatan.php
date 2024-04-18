<?php
include "./koneksi.php";
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id_akun']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== 1) {
    header("Location: ./login_ikan/login.php");
    exit();
}

// Fetch total income
$totalIncomeQuery = "SELECT SUM(total_harga) AS total_income FROM item_pesanan
                    INNER JOIN pesanan ON item_pesanan.id_pesanan = pesanan.id_pesanan
                    WHERE pesanan.status = 'Paid'";
$totalIncomeResult = $conn->query($totalIncomeQuery);
$totalIncome = $totalIncomeResult->fetch_assoc()['total_income'];

// Fetch income details
$incomeDetailsQuery = "SELECT pesanan.id_pesanan, DATE(pesanan.order_date) AS order_date, SUM(item_pesanan.total_harga) AS total_harga
                      FROM pesanan
                      INNER JOIN item_pesanan ON pesanan.id_pesanan = item_pesanan.id_pesanan
                      WHERE pesanan.status = 'Paid'
                      GROUP BY pesanan.id_pesanan, order_date";
$incomeDetailsResult = $conn->query($incomeDetailsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Report</title>
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
            color: #3559E0;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover{
           background-color: #d2d2d2;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3559E0;
            color: #fff;
        }
        .admin-button {
            background-color: #1E3859;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius:6px;
        }   
        .buttona{
            background-color: #74A9F0;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius:6px;
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
    <?php include "navbar.php"; ?>
    <div class="container">
        <a href="admin.php" class="buttona">Back Home</a>
        <h2>Income Report</h2>
        <p>Total Income: Rp. <?php echo number_format($totalIncome, 2); ?></p>
        <table>
            <tr>
                <th>Invoice ID</th>
                <th>Order Date</th>
                <th>Total Income</th>
            </tr>
            <?php while ($row = $incomeDetailsResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id_pesanan']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['order_date'])); ?></td>
                    <td>Rp. <?php echo number_format($row['total_harga'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <div class="footer">
        &copy; Pemuda FPFisher
    </div>
</body>

</html>
