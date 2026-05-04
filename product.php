<?php 
include 'admin/connection.php';
session_start();

// Redirect to shop if no product id
if(!isset($_GET['id'])) {
    header("Location: shop.php");
    die();
}

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} 

$product_id = intval($_GET['id']);
$stmt = $con->prepare("SELECT * FROM products WHERE Product_ID = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script>alert("Product not found! Going back.");
            window.history.back();</script>';
    die();
}

$row = $result->fetch_assoc();

// Recently viewed cookie
$recently_viewed = isset($_COOKIE['recently_viewed']) ? json_decode($_COOKIE['recently_viewed'], true) : [];

// Remove duplicates
if (($key = array_search($product_id, $recently_viewed)) !== false) {
    unset($recently_viewed[$key]);
}

// Add to front
array_unshift($recently_viewed, $product_id);

// Limit to 4 items
$recently_viewed = array_slice($recently_viewed, 0, 4);

// Store cookie
setcookie('recently_viewed', json_encode($recently_viewed), time() + (86400 * 7), "/"); 

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Product</title>
    <style>
        input[type="number"] {
            font-family: 'Changa One';
            font-weight: normal;
            font-size: 16px;
            color: #5A21A5;
            -moz-appearance: textfield;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<section class="photocard-section">
    <div class="photocard-container">
        <div class="back-link-container">
            <a href="shop.php" class="back-btn">&larr; Back to Shop</a>
        </div>
        <div class="photocard-top">
            <div class="photocard-img">
                <img src="products/<?= htmlspecialchars($row['Product_Image']); ?>" alt="<?= htmlspecialchars($row['Photocard_Title']) ?>">
            </div>
            <div class="photocard-desc">
                <h1><?= htmlspecialchars($row['Photocard_Title']); ?></h1>
                <p class="photocard-price">PHP <?= number_format($row['Price'],2); ?></p>
                <h2>Description:</h2>
                <p class="photocard-desc-text"><?= nl2br(htmlspecialchars($row['Description'])); ?></p>
                <p class="photocard-qty">Quantity: <?= intval($row['Quantity']); ?></p>
            </div> 
        </div>

        <div class="photocard-bottom">
            <div class="quantity-picker">
                <form action="cart_process.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $row['Product_ID']; ?>">
                    <label for="quantity_input" class="quantity-label">Quantity</label>
                    <div class="quantity-picker">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                        <input type="number" name="quantity" id="quantity_input" value="1" min="1" max="<?= intval($row['Quantity']); ?>">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                    </div>
                    <button class="add-cart-btn" type="submit" name="add_to_cart">ADD TO CART</button>
                </form>
            </div>

            <div class="button-row">
                <div class="buy-button">
                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="checkout_items" value="<?= $row['Product_ID']; ?>">
                        <input type="hidden" name="num_ordered" id="quantity_buy" value="1" max="<?= intval($row['Quantity']); ?>">
                        <input type="hidden" name="price" value="<?= $row['Price'] ?>">
                        <button class ="buy-btn" type="submit" name="buy_now">BUY NOW</button>
                    </form>
                </div>

                <div class="trade-button">
                    <?php if ($row['Tradable']) : ?>
                        <form action="trade_upload.php" method="GET">
                            <input type="hidden" name="Product_ID" value="<?= $row['Product_ID']; ?>">
                            <input type="hidden" name="Product_Description" value="<?= htmlspecialchars($row['Description']); ?>">
                            <input type="hidden" name="Product_Name" value="<?= htmlspecialchars($row['Photocard_Title']); ?>">
                            <input type="hidden" name="Product_Image" value="<?= $row['Product_Image']; ?>">
                            <button class="trade-btn" type="submit" name="trade">TRADE</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="added-cart-alert">
                <?php if (isset($_SESSION['cart_success'])): ?>
                    <p class="cart-message"><?= htmlspecialchars($_SESSION['cart_success']); ?></p>
                    <?php unset($_SESSION['cart_success']); ?>
                <?php endif; ?>
            </div>
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
function changeQuantity(change) {
    let quantityInput = document.getElementById('quantity_input');
    let quantityBuy = document.getElementById('quantity_buy');
    let currentValue = parseInt(quantityInput.value);
    let maxValue = parseInt(quantityInput.max);
    let minValue = parseInt(quantityInput.min);

    let newValue = currentValue + change;
    if (newValue >= minValue && newValue <= maxValue) {
        quantityInput.value = newValue;
        quantityBuy.value = newValue;
    }
}

document.getElementById('quantity_input').addEventListener('input', function () {
    let maxValue = parseInt(this.max);
    let minValue = parseInt(this.min);
    let value = parseInt(this.value);

    if(value > maxValue) value = maxValue;
    if(value < minValue) value = minValue;

    this.value = value;
    document.getElementById('quantity_buy').value = value;
});
</script>

</html>
