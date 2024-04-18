<?php
session_start();
include "../koneksi.php";

// Check if the user is logged in as an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id_ikan'])) {
    $id_ikan = $_GET['id_ikan'];

    // Delete the fish from the database
    $deleteSql = "DELETE FROM ikan WHERE id_ikan=$id_ikan";

    if (mysqli_query($conn, $deleteSql)) {
        echo "Fish deleted successfully";
    } else {
        echo "Error deleting fish: " . mysqli_error($conn);
    }

    header("Location: read-product.php");
    exit();
} else {
    echo "Invalid fish ID";
    exit();
}
?>
