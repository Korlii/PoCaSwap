<?php
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/add_product.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Climate+Crisis&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../images/PoCaSwap Logo.ico"/>
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="header">
        <div class="logo">
        <a href="../index.php">
            <img src="../images/PoCaSwap Logo.png" alt="Logo">
        </a>
            <div class="title">
                <span>PoCaSwap</span>
            </div>
        </div>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Pending Orders</a>
            <a href="pending_trades.php">Pending Trades</a>
        </div>
        </div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $tradable = isset($_POST['tradable']) ? 1 : 0;

    // Upload directory outside the admin folder
    $target_dir = realpath(__DIR__ . '/../products/') . '/';
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Default image if no file is uploaded
    $image_name = "default.png";

    if (!empty($_FILES['image']['name'])) {
        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ["jpg", "jpeg", "png"];
        $allowed_mimes = ["image/jpeg", "image/png"];
        $file_mime = mime_content_type($file_tmp);

        // Validate file type
        if (in_array($file_ext, $allowed_exts) && in_array($file_mime, $allowed_mimes)) {
            $image_name = $file_name; // Keep original filename
            $target_file = $target_dir . $image_name;

            // Move the file to the correct folder
            move_uploaded_file($file_tmp, $target_file);
        }
    }

    // Insert into database
    $stmt = $con->prepare("INSERT INTO products (Photocard_Title, Description, Price, Quantity, Tradable, Product_Image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $name, $description, $price, $quantity, $tradable, $image_name);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="number" name="price" placeholder="Price" required><br>
    <input type="number" name="quantity" placeholder="Quantity" required><br>
    <label><input type="checkbox" name="tradable"> Tradeable</label><br>
    <input type="file" name="image" accept=".jpg, .jpeg, .png"><br>
    <button type="submit">Add Product</button><br>
</form>

</body>
</html>
