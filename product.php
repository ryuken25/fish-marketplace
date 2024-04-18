<?php
include "koneksi.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM ikan";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product</title>
        <style>
        *{
            margin: 0;
        }

        a{
            text-decoration: none;
        }
        .produk {
            margin-top: 150px;
            margin-left: 30px;
            margin-bottom: 100px;
            padding: 20px;
            color: #3559E0;
            font-size: 64px;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-content: center;
            padding: 20px;
            margin-bottom: 100px;
        }

        .product-item {
            background-color: #7DB6DF;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            max-width: 50%;
            border-radius: 8px;
            transition: transform 0.3s ease-in-out;
            margin: auto; /* Center horizontally */
            position: relative; /* Added */
        }

        .product-item:hover {
            transform: scale(1.05);
        }

        .product-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        h3 {
            color: #414141;
            margin-bottom: 10px;
        }

        .p {
            color: #414141;
            position: relative; 
        }

        .cart-logo {
            position: absolute;
            bottom: -10px;
            right: -10px;
            max-width: 30px;
            height: auto;
        }
        .left-0{
            position: relative;
            left: 0px;
            color: #414141;
        }
    </style>
    </head>
    <body>
        <h1 class="produk">Product</h1>
        <div class="product-container">
            <?php
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <a href="detail-product.php?id_ikan=<?php echo $row['id_ikan']; ?>" class="product-item">
                    <img src="./ikan_image/<?php echo $row['gambar_ikan']; ?>" alt="<?php echo $row['nama_ikan']; ?>">
                    <div>
                        <h3><?php echo $row['nama_ikan']; ?></h3>
                        <p class="p"><?php echo $row['jenis_ikan']; ?></p>
                        <p class="left-0">Harga: <?php echo $row['harga_ikan']; ?> <img class="cart-logo" src="./ikan_image/cart.png" alt="Cart"></p>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </body>
    <script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  </script>
    </html>

    <?php
} else {
    echo "No products found.";
}

// Close the database connection
$conn->close();
?>
