<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== 1) {
    // Redirect to another page (e.g., an error page or the homepage)
    header("Location: index.php");
    exit();
}

// Include your database connection file (modify as needed)
include 'koneksi.php';

// Fetch invoice details from the database (modify SQL query as needed)
$invoiceId = $_GET['id']; // Assuming you have the invoice ID in the URL
$invoiceQuery = "SELECT * FROM invoices WHERE id = $invoiceId";
$invoiceResult = mysqli_query($conn, $invoiceQuery);

if (!$invoiceResult) {
    die("Error fetching invoice details: " . mysqli_error($conn));
}

$invoice = mysqli_fetch_assoc($invoiceResult);

// Include your header and navigation files
include 'header.php';
include 'navbar.php';
?>

<!-- Your HTML and PHP code for displaying invoice details -->
<div class="container">
    <h2>Invoice Details</h2>

    <table>
        <tr>
            <th>Invoice ID</th>
            <th>Customer Name</th>
            <th>Total Amount</th>
            <!-- Add more columns as needed -->
        </tr>
        <tr>
            <td><?php echo $invoice['id']; ?></td>
            <td><?php echo $invoice['customer_name']; ?></td>
            <td><?php echo $invoice['total_amount']; ?></td>
            <!-- Add more cells as needed -->
        </tr>
    </table>

    <!-- Additional HTML/PHP code for displaying other details -->

</div>

<?php
// Include your footer file
include 'footer.php';
?>
