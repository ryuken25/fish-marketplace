
<?php
session_start(); // Add this line

include "./koneksi.php";

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

$id_ikan = isset($_GET['id_ikan']) ? $_GET['id_ikan'] : null;

if ($id_ikan !== null) {
    $selectSql = "SELECT * FROM ikan WHERE id_ikan=$id_ikan";
    $result = mysqli_query($conn, $selectSql);

    if ($result) {
        $fish = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching fish details: " . mysqli_error($conn);
        exit();
    }
} else {
    echo "Invalid fish ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
       /* CSS */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f1f1f1;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius:10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

.product-details {
    margin-top: 20px;
}

.product-details h3 {
    color: #333;
}

.product-details p {
    margin: 10px 0;
}

.product-details img {
    max-width: 100%;
    height: auto;
    margin-top: 10px;
}

form {
    margin-top: 20px;

}

form label {
    display: block;
    margin-bottom: 5px;
}

form input[type="number"],
form button {
    padding: 8px;
}

form input[type="number"] {
    width: 60px;
}

form button {
    background-color: #1E3859;
    color: #fff;
    border: none;
    cursor: pointer;
}

form button:hover {
    background-color: #305080;
}

.navbar {
    background-color: #96B6C5;
    padding: 10px;
    width: auto;
    height: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 24px;
    color: white;
    cursor: pointer;
}

.nav-list {
    list-style: none;
    display: flex;
    align-items: center;
}

.nav-list li {
    margin: 0 15px;
    color: white;
}

.nav-list a {
    text-decoration: none;
    color: white;
}

.admin-button {
    background-color: #1E3859;
    color: white;
    padding: 8px 15px;
    border: none;
    cursor: pointer;
    border-radius:8px;
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
    color:white;
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
    <title>Keranjang Belanja</title>
</head>

<body>
<?php include "navbar.php"; ?>

    <div class="container">
    <div class="containers">
    <a href="index.php" class="button">Back Home</a>
</div>
        <h2>Keranjang Belanja</h2>
        <?php
        if (!empty($_SESSION['keranjang'])) {
            foreach ($_SESSION['keranjang'] as $id_ikan => $item) {
                echo "<p>ID Ikan: $id_ikan, Quantity: {$item['quantity']}, Harga Ikan: {$item['harga_ikan']}</p>";
            }
        } else {
            echo "Keranjang belanja kosong.";
        }
        ?>
  
        <h2>Product Details</h2>
        <?php
        if (isset($fish)) {
        ?>
            <div>
                <h3><?php echo $fish['nama_ikan']; ?></h3>
                <p><strong>ID:</strong> <?php echo $fish['id_ikan']; ?></p>
                <p><strong>Harga:</strong> <?php echo $fish['harga_ikan']; ?></p>
                <p><strong>Jenis:</strong> <?php echo $fish['jenis_ikan']; ?></p>
                <p><strong>Deskripsi:</strong> <?php echo $fish['deskripsi']; ?></p>
                <img src="<?php echo "./ikan_image/" . $fish['gambar_ikan']; ?>" alt="Product Image">
                <form method="post" action="">
                    <input type="hidden" name="id_ikan" value="<?php echo $fish['id_ikan']; ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" required>
                    <input type="hidden" name="harga_ikan" value="<?php echo $fish['harga_ikan']; ?>">
                    <button type="submit">Add to keranjang</button>
                </form>
                <br>
               
            </div>
        <?php
        } else {
            echo "Fish details not found.";
        }
        ?>

    </div>
    <div class="footer">
        &copy; Pemuda FPFisher
        </div>
</body>

</html>
