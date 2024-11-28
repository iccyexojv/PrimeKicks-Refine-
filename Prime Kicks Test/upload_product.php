<?php
$servername = "localhost";
$username = "root"; // Update if necessary
$password = ""; // Update if necessary
$dbname = "prime_kicks"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['upload'])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $size_range = $_POST['size_range'];
    $color = $_POST['color'];
    $description = $_POST['description'];

    // Handle image upload
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_folder = 'uploads/' . $image_name;

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        // Insert data into database
        $sql = "INSERT INTO products (title, price, size_range, color, description, img_path) 
                VALUES ('$title', '$price', '$size_range', '$color', '$description', '$image_folder')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product uploaded successfully!'); window.location.href = 'order_list.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}

$conn->close();
?>
