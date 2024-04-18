
<style>
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
        font-size: 30px;
        color: white;
        cursor: pointer;
    }

    .nav-list {
        list-style: none;
        display: flex;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .nav-list li {
        margin: 0 15px;
        color: white;
    }

    .nav-list a {
        text-decoration: none;
        color: white;
        align-items: center;
        display: flex; 
        flex-direction: column; 
        text-align: center; 
    }

    .nav-list p {
        margin: 0; 
    }
</style>

<?php if (isset($_SESSION['email'])) {
    $nama = $_SESSION['nama']; ?>
    <nav class="navbar">
            <div class="logo">FishPlanet Store</div>
            <ul class="nav-list">
            <?php
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
        echo '<button style="font-size: 14px; " class="admin-button" onclick="window.location.href=\'../admin.php\'">User View</button>';
    }
    ?>   
            <li style="margin-left: 10px; ">Hello <?php echo $_SESSION['nama'];?></li>
                <li><a href="../keranjang.php"><img src="../ikan_image/cart.png" alt="Cart"> </a></li>
                <li><a href="../account.php" ><img src="../ikan_image/account.png" alt="Account"> </a></li>
            </ul>
    </nav> <?php } else { ?> 
    <nav class="navbar">
    <div class="logo">FishPlanet Store</div>
    <ul class="nav-list">
    <li>Hello Guest</li>
        <li><a href="./login_ikan/login.php"><img src="./ikan_image/cart.png" alt="Cart"> </a></li>
        <li><a href="./login_ikan/login.php"><img src="./ikan_image/account.png" alt="Account"> </a></li>
    </ul>
    </nav> <?php
}
?> 
