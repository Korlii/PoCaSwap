<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/sign_up.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Sign Up</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
                <p>SIGNUP</p>
            </div>
        </nav>
    </header>

    <section class="content">
        <div class="container">
            <div class="branding">
                <img src="images/PoCaSwap Logo.png" alt="PoCaSwap Cards" class="cards">
                <h1 class="logo-text">PoCaSwap</h1>
                <p class="tagline">Shop. Swap. Collect</p>
            </div>

            <div class="login-box">
                <div class="login-circle"></div> 
                <h2 class="login-title">SIGNUP</h2>
                <!-- Form points to the separate processing script -->
                <form action="sign_up_process.php" method="POST">
                    <label class="login-text" for="username">Username:</label>
                    <input type="text" name="username" placeholder="Username" id="username" required><br>

                    <label class="login-text" for="firstname">First Name:</label>
                    <input type="text" name="Firstname" placeholder="Juan" required><br>

                    <label class="login-text" for="lastname">Last Name:</label>
                    <input type="text" name="Lastname" placeholder="Dela Cruz" required><br>

                    <label class="login-text" for="phone_number">Phone Number:</label>
                    <input type="tel" name="phone" placeholder="09XXXXXXXXXX" required maxlength="11"><br>

                    <label class="login-text" for="password">Password:</label>
                    <input type="password" name="password" placeholder="Password" required>

                    <button class="signup-btn" type="submit" name="sign_up">Sign Up</button>
                </form>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </section>
</body>
</html>
