<header>
    <nav class="navbar">
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-links" id="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="order_tracker.php">Tracker</a></li>
            <li><a href="redirection.php">Trades</a></li>
        </ul>
        <div class="logo">
            <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
        </div>
        <div class="profile" id="profile">
            <a href="cart.php" class="cart-link"><img src="images/shopping_bag.png" alt="shopping bag"></a>
            <div class="user-actions">
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
                        echo "<a href='login.php'>Login</a> <span class='separator'>|</span> <a href='sign_up.php'>Sign Up</a>";
                    }
                ?>
            </div>
        </div>
    </nav>
</header>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const hamburger = document.getElementById("hamburger");
        const navLinks = document.getElementById("nav-links");
        const profile = document.getElementById("profile");

        if (hamburger) {
            hamburger.addEventListener("click", function() {
                hamburger.classList.toggle("active");
                navLinks.classList.toggle("active");
                profile.classList.toggle("active");
            });
        }
    });
</script>
