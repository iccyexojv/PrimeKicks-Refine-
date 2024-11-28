<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "prime_kicks";
$username = "root";
$password = "";
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize confirmed orders if not set
if (!isset($_SESSION['confirmed_orders'])) {
    $_SESSION['confirmed_orders'] = [];
}

// Handle adding products to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'], $_POST['price'], $_POST['img'])) {
    $product = [
        'title' => $_POST['title'],
        'price' => $_POST['price'],
        'img' => $_POST['img']
    ];
    $_SESSION['cart'][] = $product;
    echo json_encode($_SESSION['cart']);
    exit;
}

// Handle removing products from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    $_SESSION['message'] = "Order removed successfully.";

    exit;
}

// Handle order confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $conn->prepare("INSERT INTO orders (title, price, img) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $item['title'], $item['price'], $item['img']);
        $stmt->execute();
        $_SESSION['message'] = "Order confirmed successfully.";


        // Add the order date and move confirmed item to confirmed orders
        $order_date = date('Y-m-d'); // current timestamp
        $_SESSION['confirmed_orders'][] = [
            'title' => $item['title'],
            'price' => $item['price'],
            'img' => $item['img'],
            'order_date' => $order_date
        ];
    }
    
    // Empty cart after confirmation
    $_SESSION['cart'] = [];
    header("Location: cart.php"); // Refresh page after confirmation
    exit;
}

if (isset($_SESSION['message'])) {
    echo '<p>' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Prime Kicks - Cart</title>
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
            margin-top:470px;
        }

        .cart-items, .confirmed-orders {
            display: flex;
            flex-direction: column;
            align-items: bottom;
            width: 40%;
            margin-top: 20px;
        }

        .cart-item, .confirmed-item {
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

        .cart-item img, .confirmed-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart-item h3, .confirmed-item h3 {
            font-size: 20px;
            font-weight: 600;
        }

        .cart-item p, .confirmed-item p {
            font-size: 16px;
            font-weight: 500;
            color: #e74c3c;
        }

        .remove-btn {
            text-decoration: none;
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .remove-btn:hover {
            background: #c0392b;
        }

        .btn-confirm {
            background-color: #e74c3c;
            color: #fff;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .btn-confirm:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<nav>
<div class="menu">
        <div class="logo">
            <a href="index.php">Prime Kicks</a>
        </div>
        <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="About.php">About us</a></li>
                <li><a href="pre_order.php">Pre-Order</a></li>
                <li><a href="cart.php">Mycart</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="Logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<h2>Your Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty. Start shopping!</p>
<?php else: ?>
    <div class="cart-items">
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['title']; ?>" width="100">
                <div>
                    <h3><?php echo $item['title']; ?></h3>
                    <p>Price: Nrs. <?php echo $item['price']; ?></p>
                </div>
                <a href="cart.php?remove=<?php echo $index; ?>" class="remove-btn">Remove</a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Order Confirmation Form -->
    <form id="orderForm" method="POST" action="cart.php">
        <button type="submit" name="confirm_order" class="btn-confirm">Confirm Order</button>
    </form>
<?php endif; ?>

<h2>Confirmed Orders</h2>
<?php if (empty($_SESSION['confirmed_orders'])): ?>
    <p>No confirmed orders yet.</p>
<?php else: ?>
    <div class="confirmed-orders">
        <?php foreach ($_SESSION['confirmed_orders'] as $item): ?>
            <div class="confirmed-item">
                <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['title']; ?>" width="100">
                <div>
                    <h3><?php echo $item['title']; ?></h3>
                    <p>Price: <?php echo $item['price']; ?></p>
                    <p>Order Date: <?php echo $item['order_date']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<footer>
<p>Privacy Policy | Terms and Conditions</p> 
    <p>© 2024 Prime Kicks, Inc.</p></div> 
</footer>
</body>
</html>
