<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_pesanan'])) {
        $id_pesanan = $_POST['id_pesanan'];

        // Check if the order belongs to the logged-in user to prevent unauthorized cancellation
        $id_akun = $_SESSION['id_akun'];
        $checkOrderSql = "SELECT * FROM pesanan WHERE id_pesanan = ? AND id_akun = ?";
        $stmtCheckOrder = $conn->prepare($checkOrderSql);
        $stmtCheckOrder->bind_param("ii", $id_pesanan, $id_akun);
        $stmtCheckOrder->execute();
        $resultCheckOrder = $stmtCheckOrder->get_result();

        if ($resultCheckOrder->num_rows > 0) {
            // Order belongs to the user, update the status to "Canceled"
            $updateStatusSql = "UPDATE pesanan SET status = 'Canceled' WHERE id_pesanan = ?";
            $stmtUpdateStatus = $conn->prepare($updateStatusSql);
            $stmtUpdateStatus->bind_param("i", $id_pesanan);

            if ($stmtUpdateStatus->execute()) {
                echo "Order canceled successfully.";
            } else {
                echo "Error canceling order: " . $stmtUpdateStatus->error;
            }

            $stmtUpdateStatus->close();
        } else {
            echo "Unauthorized access or order not found.";
        }

        $stmtCheckOrder->close();
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
?>
