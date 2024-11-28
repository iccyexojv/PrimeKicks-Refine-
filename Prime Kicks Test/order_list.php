<?php
$servername = "localhost";
$username = "root";
$password = ""; // Your MySQL password
$dbname = "prime_kicks"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
session_start();


// Handle removing an order
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    $_SESSION['message'] = "Order removed successfully.";


    // SQL to delete order from the database
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $removeIndex);
    $stmt->execute();

    // Redirect to refresh the page
    header("Location: order_list.php");
    exit;
}

// Handle confirming an order
if (isset($_GET['confirm']) && is_numeric($_GET['confirm'])) {
    $confirmIndex = $_GET['confirm'];
    $_SESSION['message'] = "Order confirmed successfully.";

    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    // SQL to update order status (add a field for order status if needed)
    $sql = "UPDATE orders SET order_status = 'Confirmed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $confirmIndex);
    $stmt->execute();

    // Redirect to refresh the page
    header("Location: order_list.php");
    exit;
}

// Fetch orders from the database
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Prime Kicks - Order List</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            text-align: center;
            background: #f2f2f2;
        }

        nav {
            position: static;
            background: #1b1b1b;
            width: 100%;
            padding: 5px 0;
        }

        nav .menu {
            max-width: 1250px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .menu .logo a {
            text-decoration: none;
            color: #fff;
            font-size: 35px;
            font-weight: 600;
        }

        .menu ul {
            display: inline-flex;
        }

        .menu ul li {
            list-style: none;
            margin-left: 7px;
        }

        .menu ul li:first-child {
            margin-left: 0px;
        }

        .menu ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .menu ul li a:hover {
            background: #fff;
            color: black;
        }

        footer {
            background-color: #1b1b1b;
            text-align: center;
            color: #fff;
            padding: 5px 0;
            width: 100%;
            margin-top: 440px;
        }

        .order-items {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            margin-top: 20px;
            gap :15px;
        }

        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background: #fff;
            margin: 10px 0;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .order-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            
        }

        .order-item h3 {
            font-size: 20px;
            font-weight: 600;
            
        }

        .order-item p {
            font-size: 16px;
            font-weight: 500;
            color: #e74c3c;
            
        }

        .remove-btn, .confirm-btn {
            text-decoration: none;
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
                 
        } 

        .remove-btn:hover, .confirm-btn:hover {
            background: #c0392b;
            
        }
       
    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <div class="menu">
        <div class="logo">
            <a href="index.php">Prime Kicks</a>
        </div>
        <ul>
            <li><a href="order_list.php">Order-List</a></li>
            <li><a href="product.php">New Stock</a></li>
            <li><a href="user.php">User</a></li>
            <li><a href="view_products.php">Recently uploaded</a></li>
            <li><a href="Logout_admin.php">Logout</a></li>
        </ul>
    </div>
</nav>
<br>
<h2>Welcome to Admin Panel !</h2><br>

<h2 >Your Orders</h2>

<?php if ($result->num_rows > 0): ?>
    <div class="order-items">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-item">
                <img src="<?php echo $row['img']; ?>" alt="<?php echo $row['title']; ?>" width="100">
                <div>
                    <h3><?php echo $row['title']; ?></h3>
                    <p>Price: $<?php echo $row['price']; ?></p>
                    <p>Order Date: <?php echo $row['order_date']; ?></p>
                </div>
                <a href="order_list.php?remove=<?php echo $row['id']; ?>" class="remove-btn">Remove</a>
                <a href="order_list.php?confirm=<?php echo $row['id']; ?>" class="confirm-btn">Confirm Order</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>

<!-- Footer -->
<footer>
    <p>Privacy Policy | Terms and Conditions</p>
    <p>© 2024 Prime Kicks, Inc.</p>
</footer>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
