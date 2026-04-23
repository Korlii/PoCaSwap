<?php 
    session_start();
    include 'admin/connection.php';
    
    $recently_viewed = isset($_COOKIE['recently_viewed']) ? json_decode($_COOKIE['recently_viewed'], true) : [];

    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Home</title>
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
                            } 
                            if($_SESSION['role'] === "user"){
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
    <main>
        <section class="banner">
            <img src="images/dweb_main_photo.jpg" alt="Photocard Collection">
        </section>
        
        <section class="card-collection">
            <h2>Find Your Favorite Cards</h2>
            <div class="cards">
                <div class='card'>
                    <a style="text-decoration: none;" href="product.php?id=1">
                        <img src='products/Yunah.jpg' alt='Photocard'>
                        <p>Yunah SUPER REAL ME POB M2 Debut Show</p>
                    </a>
                </div>
                <div class='card'>
                    <a style="text-decoration: none;" href="product.php?id=2">
                        <img src='products/Moka.jpg' alt='Photocard'>
                        <p>Moka SUPER REAL ME POB M2 Debut Show</p>
                    </a>
                </div>
                <div class='card'>
                    <a style="text-decoration: none;" href="product.php?id=3">
                        <img src='products/Minju.jpg' alt='Photocard'>
                        <p>Minju SUPER REAL ME POB M2 Debut Show</p>
                    </a>
                </div>
                <div class='card'>
                    <a style="text-decoration: none;" href="product.php?id=4">
                        <img src='products/Iroha.jpg' alt='Photocard'>
                        <p>Iroha SUPER REAL ME POB M2 Debut Show</p>
                    </a>
                </div>
                <div class='card'>
                    <a style="text-decoration: none;" href="product.php?id=5">
                        <img src='products/Wonhee.jpg' alt='Photocard'>
                        <p>Wonhee SUPER REAL ME POB M2 Debut Show</p>
                    </a>
                </div>
            </div>
        </section>

        <?php if (!empty($recently_viewed)):?>
        <section class="recently-viewed">
            <div class="recently-viewed-left">
                <h2>Recently Viewed</h2>
                <img src="images/pocaswap_recently_card.png" alt="Recently Viewed Icon" class="icon">
                <a href="shop.php"><button class="buy-now">Buy Now!</button></a>
            </div>
            <div class="recently-viewed-right">
                <?php
                    $placeholders = implode(',', array_fill(0, count($recently_viewed), '?'));
                    
                    // Safe prepared statement
                    $stmt = $con->prepare(
                        "SELECT * FROM products 
                         WHERE Product_ID IN ($placeholders)
                         ORDER BY FIELD(Product_ID, " . implode(',', $recently_viewed) . ")"
                    );
                    
                    $stmt->bind_param(str_repeat('i', count($recently_viewed)), ...$recently_viewed);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<a class='product' href='product.php?id={$row['Product_ID']}'>";
                        echo "<img src='products/{$row['Product_Image']}' style='width:100%; border-radius:12px;'>";
                        echo "<p><strong>{$row['Photocard_Title']}</strong></p>";
                        echo "</a>";
                    }
                ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="shop-swap">
            <div class="shop-swap-left">
                <h2>Shop. Swap. Collect.</h2>
                <p>Looking for the perfect photocard? PoCaSwap makes buying and trading effortless! Browse a selection, secure your favorites—all in one place. Start your collection today!</p>
                <a href="shop.php"><button class="shop-swap-btn">Buy Now!</button></a>
            </div>
            <div class="shop-swap-right">
                <img src="images/about_us.jpg" alt="PoCaSwap Image">
            </div>
        </section>

        <section class="testimonials">
            <div class="testimonial">
                <h3>Testimonial 1</h3>
                <p>“This website is super useful in my photocard collection journey!”</p>
                <img src="images/testimonial1.png" alt="first testimonial">
            </div>
            <div class="testimonial">
                <h3>Testimonial 2</h3>
                <p>“I really love this e-commerce about Photocards, specifically. My Discord friends and I have been finding a good and reliable website to buy from”</p>
                <img src="images/testimonial2.png" alt="second testimonial">
            </div>
            <div class="testimonial">
                <h3>Testimonial 3</h3>
                <p>“The website is really pleasing to look at!”</p>
                <img src="images/testimonial3.png" alt="third testimonial">
            </div>
        </section>

        <section class="features">
            <h2>Make your Photocard Journey Easier!</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <img src="images/thumbs.png" alt="Buy and Sell Easily">
                    <div class="feature-text">
                        <h3>Buy and Sell Easily</h3>
                        <p>Trade official K-pop photocards with fans worldwide.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <img src="images/fire.png" alt="Rare Finds">
                    <div class="feature-text">
                        <h3>Rare Finds</h3>
                        <p>Discover limited-edition and exclusive photocards.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <img src="images/shield.png" alt="Secure Transactions">
                    <div class="feature-text">
                        <h3>Secure Transactions</h3>
                        <p>Safe payments and verified sellers for worry-free deals.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <img src="images/smiley.png" alt="Community and Collection">
                    <div class="feature-text">
                        <h3>Community and Collection</h3>
                        <p>Connect with collectors and complete your bias set!</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
