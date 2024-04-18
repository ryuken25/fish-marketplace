<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== 1) {
    header("Location: index.php");
    exit();
}

function getOrderDetails($conn, $id_pesanan)
{
    $orderDetailsQuery = "SELECT * FROM pesanan WHERE id_pesanan = ?";
    $stmt = $conn->prepare($orderDetailsQuery);
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'edit':
                // Edit order logic
                break;

            case 'delete':
                if (isset($_POST['id_pesanan'])) {
                    $id_pesanan = $_POST['id_pesanan'];
                    $deleteOrderQuery = "DELETE FROM pesanan WHERE id_pesanan = ?";
                    $stmt = $conn->prepare($deleteOrderQuery);
                    $stmt->bind_param("i", $id_pesanan);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        echo "Order deleted successfully.";
                    } else {
                        echo "Error deleting order.";
                    }

                    $stmt->close();
                } else {
                    echo "Invalid request.";
                }
                break;

            default:
                
                break;
        }
    }
}

$ordersQuery = "SELECT pesanan.*, akun.nama FROM pesanan JOIN akun ON pesanan.id_akun = akun.id_akun ORDER BY pesanan.order_date DESC";
$ordersResult = $conn->query($ordersQuery);

if (!$ordersResult) {
    die("Error fetching orders: " . $conn->error);
}

$orders = $ordersResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px auto;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3559E0;
            color: #fff;
        }

        button {
            background-color: #E61700;
            color: #fff;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #d43f3a;
        }

        #crudForm {
            display: none;
            margin-top: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 300px;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button[type="button"] {
            background-color: #ff6347;
            color: #fff;
            cursor: pointer;
        }

        button[type="button"]:hover {
            background-color: #d43f3a;
        }

        .back-btn {
            top: 10px;
            left: 10px;
            background-color: #E61700;
            color: #fff;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .back-btn:hover {
            background-color: #2a4570;
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
            color: #fff;
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <?php
    include "navbar.php";
    ?>
  
    <div class="container">

    <div class="containers">
    <a href="../admin.php" class="button">Back Home</a>
    </div>
        <h2>Manage Orders</h2>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?php echo $order['id_pesanan']; ?></td>
                    <td><?php echo $order['nama']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <button onclick="deleteOrder(<?php echo $order['id_pesanan']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Additional HTML/PHP code for handling CRUD operations -->
        <div id="crudForm">
            <!-- Form for delete operation -->
            <form id="orderForm" action="pesanan-admin.php" method="post">
                <input type="hidden" id="id_pesanan" name="id_pesanan">
                <label for="status">Status:</label>
                <input type="text" id="status" name="status" required>
                <button type="submit">Save</button>
                <button type="button" onclick="cancelCrud()">Cancel</button>
            </form>
        </div>
    </div>
    <div class="footer">
        &copy; Pemuda FPFisher
    </div>
    <script>
        function deleteOrder(id_pesanan) {
            // Confirm deletion and then send AJAX request
            if (confirm('Are you sure you want to delete this order?')) {
                $.ajax({
                    type: 'POST',
                    url: 'pesanan-admin.php',
                    data: { action: 'delete', id_pesanan: id_pesanan },
                    success: function (response) {
                        console.log(response);
                        // Reload the page or update the order list
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                        alert('Error deleting order.');
                    }
                });
            }
        }

        function cancelCrud() {
            // Hide the CRUD form
            $('#crudForm').hide();
        }
    </script>
</body>

</html>
