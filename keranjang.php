<?php
session_start();
include 'koneksi.php';
// Check if the keranjang session variable is not set, initialize it as an empty array
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

// Check if the form is submitted to add an item to the keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_ikan']) && isset($_POST['quantity']) && isset($_POST['harga_ikan'])) {
        $id_ikan = $_POST['id_ikan'];
        $quantity = $_POST['quantity'];
        $harga_ikan = $_POST['harga_ikan'];
        $nama_ikan = $_POST['nama_ikan'];

        // Check if the item is already in the keranjang, update the quantity
        if (isset($_SESSION['keranjang'][$id_ikan])) {
            $_SESSION['keranjang'][$id_ikan]['quantity'] += $quantity;
        } else {
            // Add the item to the keranjang
            $_SESSION['keranjang'][$id_ikan] = array(
                'quantity' => $quantity,
                'harga_ikan' => $harga_ikan,
            );
        }

        // Optional: Redirect to prevent form resubmission on page refresh
        header("Location: keranjang.php");
        exit();
    }
}

// Initialize $fish as an empty array
$fish = array();

// Check if there's an item in the keranjang to fetch details
if (!empty($_SESSION['keranjang'])) {
    // Assuming $conn is your database connection
    include "./koneksi.php";

    // Fetch fish details for Keranjang section
    foreach ($_SESSION['keranjang'] as $id_ikan => $item) {
        $fishDetailsSql = "SELECT * FROM ikan WHERE id_ikan = $id_ikan";
        $fishDetailsResult = mysqli_query($conn, $fishDetailsSql);

        if ($fishDetailsResult) {
            $fish[$id_ikan] = mysqli_fetch_assoc($fishDetailsResult);
        } else {
            echo "Error fetching fish details: " . mysqli_error($conn);
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #3559E0;
            margin-bottom: 10px;      
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
        .update-button,
        .delete-button {
            background-color: #3559E0;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .update-button:hover,
        .delete-button:hover {
            background-color: #1f3a7c;
        }
        .checkout-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .checkout-button {
            background-color: #3559E0;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .checkout-button:hover {
            background-color: #1f3a7c;
        }
        .status-section {
            margin-top: 30px;
        }
        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .status-table th, .status-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .cancel-button {
            background-color: #ff6347;
            color: #fff;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .cancel-button:hover {
            background-color: #d43f3a;
        }
        .admin-button {
            background-color: #1E3859;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius:6px;
        }   

        .containers {
            max-width: 100px; 
            padding: 10px;
            background-color: #74A9F0;
            text-align: center; 
            border-radius:8px;
            
        }

        .button {
            text-decoration: none;
            margin-bottom: 10px;
            font-size: 16px;   
            color: #fff;
            font-weight:bold;
        }

        .containers:hover {
           background-color: #d2d2d2;
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
    <script>
        function updateItem(id_ikan, currentQuantity) {
            var newQuantity = parseInt(prompt("Enter new quantity:", currentQuantity), 10);
            if (!isNaN(newQuantity) && newQuantity >= 1) {
                $('#quantity-' + id_ikan).text(newQuantity);

                $.ajax({
                    type: "POST",
                    url: "update-item.php",
                    data: { id_ikan: id_ikan, newQuantity: newQuantity },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (error) {
                        console.log(error);
                        alert("Error updating item.");
                    }
                });
            } else {
                alert("Please enter a valid quantity.");
            }
        }

        function deleteItem(id_ikan) {
            if (confirm("Are you sure you want to delete this item?")) {
                $.ajax({
                    type: "POST",
                    url: "delete-item.php",
                    data: { id_ikan: id_ikan },
                    success: function (response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                        alert("Error deleting item.");
                    }
                });
            }
        }

        function cancelOrder(id_pesanan) {
            if (confirm("Are you sure you want to cancel this order?")) {
                $.ajax({
                    type: "POST",
                    url: "cancel-order.php",
                    data: { id_pesanan: id_pesanan },
                    success: function (response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                        alert("Error canceling order.");
                    }
                });
            }
        }
    </script>
</head>

<body>
<?php include "navbar.php"; ?>
    <div class="container">
    <div class="containers">
    <a href="index.php" class="button">Back Home</a>
</div>
        <h2>Keranjang Belanja</h2>
        <?php
        $totalBelanja = 0;

        if (!empty($_SESSION['keranjang'])) {
            echo "<table>";
            echo "<tr>
                    <th>Nama Ikan</th>
                    <th>Jenis</th>
                    <th>Quantity</th>
                    <th>Harga Ikan</th>
                    <th>Update/Delete</th>
                    <th>Total</th>
                </tr>";

            foreach ($_SESSION['keranjang'] as $id_ikan => $item) {
                $fishDetailsSql = "SELECT * FROM ikan WHERE id_ikan = $id_ikan";
                $fishDetailsResult = mysqli_query($conn, $fishDetailsSql);

                if ($fishDetailsResult) {
                    $fishDetails = mysqli_fetch_assoc($fishDetailsResult);
                    $nama_ikan = isset($fishDetails['nama_ikan']) ? $fishDetails['nama_ikan'] : 'Unknown Fish';
                    $jenis_ikan = isset($fishDetails['jenis_ikan']) ? $fishDetails['jenis_ikan'] : 'Unknown';
                } else {
                    echo "Error fetching fish details: " . mysqli_error($conn);
                    exit();
                }

                $total_belanja = $item['quantity'] * $item['harga_ikan'];
                $totalBelanja += $total_belanja;

                echo "<tr>
                        <td>$nama_ikan</td>
                        <td>$jenis_ikan</td>
                        <td><span id='quantity-$id_ikan'>{$item['quantity']}</span></td>
                        <td>{$item['harga_ikan']}</td>
                        <td>
                            <button class='update-button' onclick='updateItem($id_ikan, {$item['quantity']})'>Update</button>
                            <button class='delete-button' onclick='deleteItem($id_ikan)'>Delete</button>
                        </td>
                        <td>$total_belanja</td>
                    </tr>";
            }

            echo "<tr>
                    <td colspan='5' style='text-align:right;'>Total Harga:</td>
                    <td>$totalBelanja</td>
                </tr>";

            echo "</table>";
        } else {
            echo "<p>Keranjang belanja kosong.</p>";
        }
        ?>

        <div class="checkout-section">
            <p>Total Harga: <?php echo $totalBelanja; ?></p>
            <form method="post" action="checkout.php">
                <button type="submit" name="pay" class="checkout-button">Checkout</button>
            </form>
        </div>

        <div class="status-section">
            <h2>Status Pesanan</h2>
            <?php
            $id_akun = $_SESSION['id_akun'];
            $orderStatusSql = "SELECT * FROM pesanan WHERE id_akun = ? ORDER BY order_date DESC";
            $stmtOrderStatus = $conn->prepare($orderStatusSql);
            $stmtOrderStatus->bind_param("i", $id_akun);
            $stmtOrderStatus->execute();
            $resultOrderStatus = $stmtOrderStatus->get_result();

            if ($resultOrderStatus->num_rows > 0) {
                echo "<table class='status-table'>";
                echo "<tr>
                        <th>Invoice ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>";

                while ($order = $resultOrderStatus->fetch_assoc()) {
                    echo "<tr>
                            <td>{$order['id_pesanan']}</td>
                            <td>{$order['order_date']}</td>
                            <td>{$order['status']}</td>
                            <td>";

                    if ($order['status'] === 'Pending') {
                        echo "<button class='cancel-button' onclick=\"cancelOrder({$order['id_pesanan']})\">Batalkan Pesanan</button>";
                    }

                    echo "</td></tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No order history found.</p>";
            }

            $stmtOrderStatus->close();
            ?>
        </div>
    </div>
    <div class="footer">
        &copy; Pemuda FPFisher
    </div>
</body>

</html>

