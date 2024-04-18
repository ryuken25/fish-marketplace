<?php
session_start();
include "../koneksi.php";

// Check if the user is logged in as an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: ../index.php");
    exit();
}

$id_ikan = isset($_GET['id_ikan']) ? $_GET['id_ikan'] : null;

$successMessage = '';

if ($id_ikan !== null) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle form submission for updating fish details
        $harga_ikan = validate($_POST['harga_ikan']);
        $nama_ikan = validate($_POST['nama_ikan']);
        $jenis_ikan = validate($_POST['jenis_ikan']);
        $deskripsi = validate($_POST['deskripsi']);

        // Check if a new image is uploaded
        if (!empty($_FILES['gambar_ikan']['name'])) {
            // Delete the existing image
            if (!empty($fish['gambar_ikan'])) {
                $oldImage = $fish['gambar_ikan'];
                $oldImagePath = "../ikan_image/" . $oldImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Handle file upload for the 'gambar_ikan' field
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
                    move_uploaded_file($fileTmpName, $fileDestination);

                    // Update the 'gambar_ikan' field in the database
                    $updateSql = "UPDATE ikan SET gambar_ikan='$newFileName' WHERE id_ikan=$id_ikan";
                    if (mysqli_query($conn, $updateSql)) {
                        $successMessage .= "Fish image updated successfully. ";
                    } else {
                        echo "Error updating fish image: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "Invalid file type. Allowed file types: jpg, jpeg, png, gif.";
            }
        }

        // Update the other fish details in the database
        $updateSql = "UPDATE ikan SET harga_ikan='$harga_ikan', 
                        nama_ikan='$nama_ikan', jenis_ikan='$jenis_ikan', 
                        deskripsi='$deskripsi' WHERE id_ikan=$id_ikan";

        if (mysqli_query($conn, $updateSql)) {
            $successMessage .= "Fish details updated successfully";
        } else {
            echo "Error updating fish details: " . mysqli_error($conn);
        }
    }

    // Fetch existing details of the fish
    $selectSql = "SELECT * FROM ikan WHERE id_ikan=$id_ikan";
    $result = mysqli_query($conn, $selectSql);

    if ($result) {
        $fish = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching fish details: " . mysqli_error($conn);
    }
} else {
    echo "Invalid fish ID";
    exit();
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F1F0E8;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        li {
            display: inline;
            margin-right: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #3559E0;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 98%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #3559E0;
            font-weight: bold;
            display: inline-block;
        padding: 15px 20px;
        text-decoration: none;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #204080;
        }

        .button {
        display: inline-block;
        padding: 13px 20px;
        text-decoration: none;
        color: #fff;
        background-color: #E61700;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        }

        .button:hover {
        background-color:#414141;
        }
        #image-preview {
        margin-top: 15px;
        max-width: 100%;
        height: auto;
        border-radius: 5px;
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
    <title>Edit Product</title>
</head>

<body>
    <?php include "navbar.php"; ?>
    <?php
            if (!empty($successMessage)) {
                echo '<div style="color: green;">' . $successMessage . '</div>';
            }
            ?>
    <div class="container">
        <h2>Edit Product</h2>
        <?php
        if (isset($fish)) {
        ?>
            <form method="post" action="" enctype="multipart/form-data">
                <label for="harga_ikan">Harga Ikan:</label>
                <input type="text" name="harga_ikan" value="<?php echo $fish['harga_ikan']; ?>" required>

                <label for="nama_ikan">Nama Ikan:</label>
                <input type="text" name="nama_ikan" value="<?php echo $fish['nama_ikan']; ?>" required>

                <label for="jenis_ikan">Jenis Ikan:</label>
                <select name="jenis_ikan" required>
                    <option value="Tawar" <?php echo ($fish['jenis_ikan'] === 'Tawar') ? 'selected' : ''; ?>>Tawar</option>
                    <option value="Laut" <?php echo ($fish['jenis_ikan'] === 'Laut') ? 'selected' : ''; ?>>Laut</option>
                </select>

                <label for="gambar_ikan">Gambar Ikan:</label>
                <input type="file" name="gambar_ikan" accept="image/*" onchange="previewImage(event)">
                <img id="image-preview" src="<?php echo "../ikan_image/" . $fish['gambar_ikan']; ?>" alt="Image Preview">

                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" rows="4" required><?php echo $fish['deskripsi']; ?></textarea>

                <button type="submit">Update Product</button>
                <a href="read-product.php" class="button">Back</a>
            </form>
            <script>
                function previewImage(event) {
                    var input = event.target;
                    var preview = document.getElementById('image-preview');
                    var file = input.files[0];
                    var reader = new FileReader();

                    reader.onload = function() {
                        preview.src = reader.result;
                    };

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }
            </script>
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
