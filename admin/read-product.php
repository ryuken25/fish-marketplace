<?php
session_start();
include "../koneksi.php";

// Check if the user is logged in as an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: ../index.php");
    exit();
}

// Fetch all fish details from the database
$selectSql = "SELECT * FROM ikan";
$result = mysqli_query($conn, $selectSql);

if (!$result) {
    echo "Error fetching fish details: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">
<head>

    <title>Admin - View Products</title>
    <style>
              body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            
        }
      *{
            margin:0
        }

        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }
        table {
            margin-left: auto;
            margin-right: auto;
            width: 95%;
            border-collapse: collapse;
            margin-top: 20px;
        }


        th, td {
            padding: 10px;  
            text-align: center;
        }

        th {
            background-color: #3559E0;
            color: white;
        }

        .action-buttons {
            text-align: right;
        }

        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            padding: 5px 18px;
            background-color: #3498db;
            color: #fff;
            border-radius: 5px;
        }

        .action-buttons a.edit {
            background-color: #2685CA;
        }

        .action-buttons a.delete {
            background-color: #E61700;
        }

        .add-button {
            margin-top: 30px;
            margin-right: 3%;
            text-align: right;
        }

        .add-button a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border-radius: 5px;
        }

        .container{
            margin: 20px;
        }

        table tr:nth-child(odd) {
            background-color: #74A9F0; 
            color: white;
        }

        table tr:nth-child(even) {
         background-color: #ffffff; 
        }
        .admin-button {
        background-color: #1E3859;
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
    <?php?>
    <div class="container">
        <h2 style="    font-size: 30px ;
                        color: #3559E0 ;    
                        margin-left:3%;
                  "
        >Admin - View Products</h2>

        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table >
                    <tr >
                        <th>ID</th>
                        <th>Harga</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Deskripsi</th>
                        <th>Action</th>
                    </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['id_ikan']}</td>
                        <td>{$row['harga_ikan']}</td>
                        <td>{$row['nama_ikan']}</td>
                        <td>{$row['jenis_ikan']}</td>
                        <td>{$row['deskripsi']}</td>
                        <td class='action-buttons'>
                            <a href='edit-product.php?id_ikan={$row['id_ikan']}' class='edit'>Edit</a>
                            <a href='delete-product.php?id_ikan={$row['id_ikan']}' class='delete'>Delete</a>
                        </td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "No products found.";
        }
        ?>

        <div class="add-button">
            <a href="add-product.php">Tambah Product</a>
            <a href="../admin.php" style="background-color: #E61700;">Back To Admin </a>
        </div>
    </div>
    <div class="footer">
        &copy; Pemuda FPFisher
    </div>
</body>

</html>
