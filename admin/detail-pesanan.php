<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];

    // Use the getOrderDetails function to fetch order details
    $orderDetails = getOrderDetails($conn, $id_pesanan);

    if ($orderDetails) {
        echo json_encode($orderDetails);
    } else {
        echo json_encode(['error' => 'Error fetching order details.']);
    }
}
?>
