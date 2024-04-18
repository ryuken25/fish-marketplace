<?php
// Include necessary files and initialize session
include "./koneksi.php"; // Include your database connection file
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id_akun'])) {
    header("Location: ./login_ikan/login.php");
    exit();
}

// Check if the keranjang session variable is not set
if (empty($_SESSION['keranjang'])) {
    header("Location: keranjang.php");
    exit();
}

// Handle the payment process when the "Pay" button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    try {
        // Insert order information into the pesanan table
        $id_akun = $_SESSION['id_akun'];
        $order_date = date("Y-m-d H:i:s");
        $status = "Pending"; // Set the initial status as needed

        $insertOrderSql = "INSERT INTO pesanan (id_akun, order_date, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertOrderSql);
        $stmt->bind_param("iss", $id_akun, $order_date, $status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $id_pesanan = $stmt->insert_id;

            // Insert order items into the item_pesanan table
            foreach ($_SESSION['keranjang'] as $id_ikan => $item) {
                $quantity = $item['quantity'];

                $insertOrderItemSql = "INSERT INTO item_pesanan (id_pesanan, id_ikan, quantity) VALUES (?, ?, ?)";
                $stmtOrderItem = $conn->prepare($insertOrderItemSql);
                $stmtOrderItem->bind_param("iii", $id_pesanan, $id_ikan, $quantity);
                $stmtOrderItem->execute();
            }

            // Update order status to "Paid"
            $updateStatusSql = "UPDATE pesanan SET status = 'Paid' WHERE id_pesanan = ?";
            $stmtUpdateStatus = $conn->prepare($updateStatusSql);
            $stmtUpdateStatus->bind_param("i", $id_pesanan);
            $stmtUpdateStatus->execute();

            // Clear the keranjang after successful payment
            unset($_SESSION['keranjang']);

            // Redirect to a confirmation page or homepage
            header("Location: confirmed.php?id_pesanan=" . $id_pesanan);
            exit();
        } else {
            throw new Exception("Error processing payment. Please try again.");
        }
    } catch (Exception $e) {
        // Log the exception or show a generic error message
        echo "An error occurred: " . $e->getMessage();
    } finally {
        $stmt->close();
        $stmtOrderItem->close();
        $stmtUpdateStatus->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Your head content -->
    <style>
        /* Your styles */
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <a href="index.php">Home</a>
        <h2>Checkout</h2>

        <!-- Display order summary -->

        <form method="post" action="checkout.php">
            <button type="submit" name="pay">Pay</button>
        </form>
    </div>

</body>

</html>
