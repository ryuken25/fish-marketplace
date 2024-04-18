<?php
session_start();
include "../koneksi.php";

// Check if the user is logged in as an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for adding a new fish
    $harga_ikan = validate($_POST['harga_ikan']);
    $nama_ikan = validate($_POST['nama_ikan']);
    $jenis_ikan = validate($_POST['jenis_ikan']);
    $deskripsi = validate($_POST['deskripsi']);

    // File upload handling
    $file = $_FILES['gambar_ikan'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowedExtensions)) {
        if ($fileError === 0) {
            // Generate a unique filename with a maximum length of 10 characters
            $newFileName = substr(uniqid('', true), 0, 10) . "." . $fileExt;
            $fileDestination = "../ikan_image/" . $newFileName;

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Insert the new fish into the database, including the filename
                $insertSql = "INSERT INTO ikan (harga_ikan, nama_ikan, jenis_ikan, deskripsi, gambar_ikan) 
                              VALUES ('$harga_ikan', '$nama_ikan', '$jenis_ikan', '$deskripsi', '$newFileName')";

                if (mysqli_query($conn, $insertSql)) {
                    echo "Fish added successfully";
                    // Redirect to the read-products.php page after successful addition
                    header("Location: read-product.php");
                    exit();
                } else {
                    echo "Error adding fish to the database: " . mysqli_error($conn);
                }
            } else {
                echo "Error moving uploaded file to destination.";
            }
        } else {
            echo "Error uploading file. Error code: $fileError";
        }
    } else {
        echo "Invalid file type. Allowed file types: " . implode(', ', $allowedExtensions);
    }
}

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Your head section here -->
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F1F0E8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #3559E0;
        }
        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        label {
            margin-bottom: 8px;
           
            font-size: 1.2em;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color:#3559E0;
            color: white;
            font-size: 1.5em;
            padding: 8px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #ADC4CE;
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
</head>

<body>
    <?php include "navbar.php"; ?>


    <div class="container">
    <div class="containers">
    <a href="read-product.php" class="button">Back</a>
    </div>
        <h2>Add Product</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="harga_ikan">Harga Ikan:</label>
            <input type="number" name="harga_ikan" required>

            <label for="nama_ikan">Nama Ikan:</label>
            <input type="text" name="nama_ikan" required>

            <label for="jenis_ikan">Jenis Ikan:</label>
            <select name="jenis_ikan" required>
                <option value="Tawar">Tawar</option>
                <option value="Laut">Laut</option>
            </select>

            <label for="gambar_ikan">Gambar Ikan:</label>
            <input type="file" name="gambar_ikan" accept="image/*" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" rows="4" required></textarea>

           
            <button type="submit">Add Product</button>

        </form>
        
    </div>
    <div class="footer">
        &copy; Pemuda FPFisher
    </div>
</body>

</html>
