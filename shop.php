<?php
include 'admin/connection.php';
session_start();

$tradable_filter = isset($_GET['tradable']) ? $_GET['tradable'] : 'all';

// Build query based on filter
$products = [];
$query = "SELECT Product_ID, Product_Image, Photocard_Title, Price, Tradable FROM products";
if ($tradable_filter === 'yes') {
    $query .= " WHERE Tradable = 1";
} elseif ($tradable_filter === 'no') {
    $query .= " WHERE Tradable = 0";
}

$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Shop</title>
</head>
<body>
<header>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="order_tracker.php">Tracker</a></li>
            <li><a href="redirection.php">Trades</a></li>
        </ul>
        <div class="logo">
            <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
        </div>
        <div class="profile">
            <a href="cart.php"><img src="images/shopping_bag.png" alt="shopping bag"> 
                <?php 
                if(isset($_SESSION['role'])){
                    if($_SESSION['role'] === "admin"){
                        echo "<a href='admin/dashboard.php'>Dashboard</a>";
                    } elseif($_SESSION['role'] === "user"){
                        echo "<p>".htmlspecialchars($_SESSION['username'])."</p>";
                    }
                    echo "<div class='logout'>".
                        "<form action='logout.php' method='post'>".
                            "<button class='logout-btn' type='submit' name='logout'>".
                                "<img src='images/logout_button.png' alt='Log out' class='logout-img'>".
                            "</button>
                        </form>
                    </div>";
                } else {
                    echo "<a href='login.php'>Login</a> <a>|</a> <a href='sign_up.php'>Sign Up</a>";
                }
                ?>
            </a>
        </div>
    </nav>
</header>

<section class="products-section">
    <div class="hero-container">
        <img src="images/shop.jpg" alt="Hero Image" class="hero-image">
    </div>

    <div class="products-display">
        <div class="filter-container">
            <label for="tradable">Filter by:</label>
            <select id="tradable" onchange="filterProducts()">
                <option value="all" <?= ($tradable_filter === 'all') ? 'selected' : '' ?>>All</option>
                <option value="yes" <?= ($tradable_filter === 'yes') ? 'selected' : '' ?>>Tradable</option>
                <option value="no" <?= ($tradable_filter === 'no') ? 'selected' : '' ?>>Non-Tradable</option>
            </select>
        </div>

        <div class="container">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?= htmlspecialchars($product['Product_ID']); ?>">
                            <div class="product-image-container">
                                <img src="products/<?= !empty($product['Product_Image']) ? htmlspecialchars($product['Product_Image']) : 'default.png' ?>" 
                                     alt="<?= htmlspecialchars($product['Photocard_Title']); ?>">
                            </div>
                        </a>
                        <div class="product-title"><?= htmlspecialchars($product['Photocard_Title']); ?></div>
                        <div class="product-price">PHP <?= number_format($product['Price'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">No products available</p>
            <?php endif; ?>
        </div>
    </div>
</section>

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

<script>
function filterProducts() {
    var tradable = document.getElementById('tradable').value;
    window.location.href = "shop.php?tradable=" + tradable;
}
</script>
</body>
</html>
