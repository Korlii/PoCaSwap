<?php
session_start();
include 'admin/connection.php';

    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }

    $stmt = $con->prepare("SELECT *, c.quantity FROM cart c
                                JOIN products p ON c.Product_ID = p.Product_ID 
                                WHERE c.User_ID = ?;");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;
    ($result->num_rows > 0) ? $_SESSION['cart'] = 'has_items' : $_SESSION['cart'] = 'empty';
    $stmt->close();
    $con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Shopping Cart</title>
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
                            if($_SESSION['role'] === "admin" ){
                                echo "<a href='admin/dashboard.php'>Dashboard</a>";
                            } if(($_SESSION['role']) === "user"){
                            echo "<p>".$_SESSION['username']."</p>";
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
    <section class="cart-section">
        <?php if($_SESSION['cart'] == 'has_items'): ?>
        <form id="checkoutForm" action="checkout.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="checkout_items[]" value="<?= $row['Product_ID']; ?>" 
                                data-price="<?= floatval($row['Price']); ?>"
                                data-quantity="<?= intval($row['quantity']); ?>" onchange="updateTotal()">
                                <img src="products/<?= $row['Product_Image']; ?>" width="50">
                                <?= $row['Photocard_Title']; ?>
                            </td>
                            <td>PHP <?= $row['Price']; ?></td>
                            <td>
                                <div class="quantity-container">
                                    <a href="cart_process.php?minus=<?= $row['Product_ID']; ?>">-</a>
                                    <span><?= $row['quantity']; ?></span>
                                    <a href="cart_process.php?add=<?= $row['Product_ID']; ?>&qty=<?= $row['quantity']; ?>">+</a>
                                </div>
                            </td>
                            <td>
                                <a class="delete-btn" href="cart_process.php?remove=<?= $row['Product_ID']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="total-container">
                <p class="total-price">Total: <span id="totalPrice">PHP 0.00</span></p>
            </div>
            <input type="hidden" name="total_price" id="totalPriceInput" value="0">
            <button type="submit" name="cart_checkout" id="checkoutBtn" class="checkout-btn">Checkout</button>
            <a href="cart_process.php?clear=true" class="clear-cart">Clear Cart</a>
        </form>
        <div class="cart-empty">
            <?php else: ?>
            <p>Your cart is empty.</p>
            <?php endif; ?>
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
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let checkboxes = document.querySelectorAll('input[name="checkout_items[]"]');
        let checkoutBtn = document.getElementById('checkoutBtn');

        function checkSelection() {
            let checked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            checkoutBtn.disabled = !checked;
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkSelection);
        });

        checkSelection();
    });

    document.addEventListener("DOMContentLoaded", function () {
        let checkboxes = document.querySelectorAll('input[name="checkout_items[]"]');
        let totalPriceElement = document.getElementById('totalPrice');
        let checkoutBtn = document.getElementById('checkoutBtn');

        function updateTotal() {
            let total = 0;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    let price = parseFloat(checkbox.dataset.price) || 0;
                    let quantity = parseInt(checkbox.dataset.quantity) || 1;
                    total += price * quantity;
                }
            });

            totalPriceElement.textContent = `PHP ${total.toFixed(2)}`; 
            document.getElementById('totalPriceInput').value = total; 
            checkoutBtn.disabled = total === 0; 
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        updateTotal();
    });

</script>

</html>
