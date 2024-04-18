<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            
        }

        h2 {
            color: #1F3A7C;
            margin-bottom: 20px;
        }

        .profile-info {
            margin-bottom: 30px;
        }

        .profile-info p {
            margin: 10px 0;
            color: #333;
            font-size: 16px;
        }

        .back-button {
            background-color: #1F3A7C;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size:16px;
            font-weight:bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #3559E0;
        }
        .containers {
            max-width: 100px; 
            text-align: center;
        }

        .logout-link {
            display: inline-block;
            margin-top: 20px;
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #bd2130;
        }

        .toggle-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            margin-left: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .toggle-button:hover {
            background-color: #218838;
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
    <?php
    session_start();
    include "koneksi.php";

    if (isset($_SESSION['email'])) {
        $nama = $_SESSION['nama'];
        $email = $_SESSION['email'];

        $sql = "SELECT password, alamat FROM akun WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $password = isset($row['password']) ? '***' : $row['password'];
            $alamat = isset($row['alamat']) ? $row['alamat'] : '***';
        } else {
            $password = '***';
            $alamat = '***';
        }
    ?>
        <div class="container">
            <div class="containers">
            <button class="back-button" onclick="goBack()">Back</button>
            </div>
            <h2>Profile Information</h2>
            <div class="profile-info">
                <p><strong>Nama:</strong> <?php echo $nama; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Password:</strong> <span id="passwordPlaceholder"><?php echo $password; ?></span> <button class="toggle-button" onclick="togglePassword()">Toggle Password</button></p>
                <p><strong>Alamat:</strong> <?php echo $alamat; ?></p>
                <a href="logout.php" class="logout-link">Logout</a>
            </div>
        </div>
        <div class="footer">
        &copy; Pemuda FPFisher
        </div>
        <script>
            function goBack() {
                window.history.back();
            }

            var passwordPlaceholder = document.getElementById('passwordPlaceholder');
            var passwordVisible = false;

            function togglePassword() {
                if (passwordVisible) {
                    passwordPlaceholder.innerText = '<?php echo $password; ?>';
                } else {
                    passwordPlaceholder.innerText = '<?php echo $row['password']; ?>';
                }
                passwordVisible = !passwordVisible;
            }
        </script>
    <?php } ?>
</body>

</html>
