<?php
session_start();
include "./koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jualan Ikan</title>
    <style>
html,body {
    font-family: Arial, sans-serif;
}
.admin-button {
    background-color: #1E3859;
    color: white;
    padding: 8px 15px;
    border: none;
    cursor: pointer;
    border-radius:4px;
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
.flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
}
.container-page {
    max-width: 100%;
}
.container-page h1 {
    font-size: 36px;
    color: #333;
    margin-bottom: 10px;
}
.container-page p {
    font-size: 18px;
    color: #666;
    margin-bottom: 20px;
}
.container-page button {
    background-color: #96B6C5;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.landing_page {
    flex: 1; 
}
.fisherman {
    margin-left:10%;
    width: 90%;
    border-radius: 8px; 
}
    </style>
</head>
<body>
    <?php
    include "navbar.php";
    ?>
  <div class="container">
  <div class="flex">
        <div class="container-page">
            <h1>Welcome to the FishPlanet Store</h1>
            <p>Discover the Fun of Shopping for Fish Online at FishPlanet Easier, Faster, More Fun!</p>
            <button style="border-radius:6px;"><a href="#section1" style="text-decoration: none; color:black; ">Product</a></button>
        </div>
        <div class="landing_page">
            <img class="fisherman" src="./ikan_image/fisherman_landingpage.jpg" alt="">
        </div>
    </div>

    <section id="section1">
        <?php
        include "./product.php";
        ?>
    </section>

        <div class="footer">
        &copy; Pemuda FPFisher
        </div>
  
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
