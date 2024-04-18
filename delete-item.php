<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_ikan'])) {
        $id_ikan = $_POST['id_ikan'];

        // Check if the item exists in the keranjang
        if (isset($_SESSION['keranjang'][$id_ikan])) {
            // Remove the item from the keranjang
            unset($_SESSION['keranjang'][$id_ikan]);

            echo "Item deleted successfully.";
        } else {
            echo "Item not found in the keranjang.";
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
?>
