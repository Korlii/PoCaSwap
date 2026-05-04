<?php
session_start();
include 'admin/connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if user has trades
if (isset($_SESSION['user_id'])) {
    $stmt = $con->prepare("SELECT username FROM trade WHERE username = ?");
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: order_tracker.php?trade=true");
        exit();
    }
}

// Photocard class
class Photocard {
    public $title;
    public $image;

    public function __construct($title, $image) {
        $this->title = $title;
        $this->image = $image;
    }

    public function displayCard() {
        return "
        <div class='card'>
            <img src='".htmlspecialchars($this->image)."' alt='".htmlspecialchars($this->title)."'>
        </div>";
    }
}

$chooseCard = new Photocard("Choose a Card", "images/choose_card.png");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/redirection.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Choose a Card!</title>
</head>
<body>
<?php include 'header.php'; ?>

<div class="trade-container">
    <div class="container-left">
        <h2>Choose a card to trade!</h2>
        <a href="shop.php?tradable=yes" class="trade-button">Check Here!</a>
    </div>
    <div class="container-right">
        <?php echo $chooseCard->displayCard(); ?>
    </div>
</div>

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
