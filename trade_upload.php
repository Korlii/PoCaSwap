<?php
include 'admin/connection.php'; 
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve GET parameters safely
$Product_ID = isset($_GET['Product_ID']) ? intval($_GET['Product_ID']) : null;
$Product_Name = isset($_GET['Product_Name']) ? $_GET['Product_Name'] : 'Unknown Product';
$Product_Description = isset($_GET['Product_Description']) ? $_GET['Product_Description'] : 'No description available';
$Product_Image = isset($_GET['Product_Image']) ? $_GET['Product_Image'] : 'default.jpg';

// Redirect if no product ID provided
if (!$Product_ID) {
    header("Location: shop.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardName = trim($_POST['Trade_Name']);
    $description = trim($_POST['Trade_Description']);
    $username = $_SESSION['username'];
    $postProductID = intval($_POST['Product_ID']); // Ensure integer

    if (!empty($cardName) && !empty($description) && isset($_FILES['Trade_Offer'])) {
        // Handle file upload
        $fileTmp = $_FILES['Trade_Offer']['tmp_name'];
        $fileName = $_FILES['Trade_Offer']['name'];
        $fileType = $_FILES['Trade_Offer']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Invalid file type. Only JPG, PNG, GIF allowed.");
        }

        // Unique filename to avoid collisions
        $imageName = uniqid() . "_" . basename($fileName);
        $uploadPath = "trades/" . $imageName;

        if (move_uploaded_file($fileTmp, $uploadPath)) {
            // Insert trade into DB
            $stmt = $con->prepare("INSERT INTO trade (Product_ID, Trade_Name, Trade_Description, Trade_Offer, username, Trade_Status) 
                                    VALUES (?, ?, ?, ?, ?, 'Pending')");
            $stmt->bind_param("issss", $postProductID, $cardName, $description, $imageName, $username);

            if ($stmt->execute()) {
                // Redirect to avoid form resubmission
                header("Location: trade_upload.php?success=1&Product_ID=$postProductID&Product_Name=" . urlencode($Product_Name) . "&Product_Description=" . urlencode($Product_Description) . "&Product_Image=" . urlencode($Product_Image));
                exit();
            } else {
                die("Database error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            die("Failed to upload image.");
        }
    } else {
        die("All fields are required.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Upload</title>
    <link rel="stylesheet" href="css/trade_upload.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'header.php'; ?>

    <div class="trade-container">
        <!-- Trade Offer Section -->
        <div class="trade-offer">
            <h2>Upload Trade Offer</h2>

            <?php if (isset($_GET['success'])): ?>
                <p class="success-msg">Trade submitted successfully!</p>
            <?php endif; ?>

            <form action="trade_upload.php" method="post" enctype="multipart/form-data">
                <label>Trade Name:</label>
                <input type="text" name="Trade_Name" required><br>

                <label>Trade Description:</label>
                <textarea name="Trade_Description" required></textarea><br>

                <label>Upload Image:</label>
                <input type="file" name="Trade_Offer" accept="image/*" required><br>

                <input type="hidden" name="Product_ID" value="<?= htmlspecialchars($Product_ID) ?>">

                <button type="submit" class="trade-btn">Trade!</button>
            </form>
        </div>

        <!-- Requested Trade Section -->
        <div class="trade-request">
            <h2>Product You Want</h2>
            <div class="requested-product">
                <img src="products/<?= htmlspecialchars($Product_Image) ?>" alt="<?= htmlspecialchars($Product_Name) ?>">
                <p><?= htmlspecialchars($Product_Name) ?></p>
                <p><?= htmlspecialchars($Product_Description) ?></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-wrapper">
            <div class="footer-center">
                <h2>PoCaSwap</h2>
                <p>Shop. Swap. Collect</p>
            </div>

            <div class="footer-bottom">
                <div class="footer-left">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="order_tracker.php">Tracker</a></li>
                        <li><a href="redirection.php">Trades</a></li>
                        <li><a href="cart.php">Shopping Bag</a></li>
                    </ul>
                </div>

                <div class="footer-right">
                    <a href="https://instagram.com/pocaswap" target="_blank"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="https://www.facebook.com/pocaswap" target="_blank"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="https://x.com/ssmucart" target="_blank"><img src="images/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
