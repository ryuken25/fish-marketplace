<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_ikan']) && isset($_POST['newQuantity'])) {
        $id_ikan = $_POST['id_ikan'];
        $newQuantity = $_POST['newQuantity'];

        // Validate $newQuantity (e.g., ensure it's a positive integer)

        // Update the session data
        if (isset($_SESSION['keranjang'][$id_ikan])) {
            $_SESSION['keranjang'][$id_ikan]['quantity'] = $newQuantity;
            echo "Item updated successfully.";
        } else {
            echo "Item not found in the cart.";
        }
    } else {
        echo "Invalid data received.";
    }
} else {
    echo "Invalid request method.";
}
?>
