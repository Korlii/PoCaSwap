<?php
session_start();

class AdminDashboard {
    private $title = "PoCaSwap";
    private $navLinks = [
        "dashboard.php" => "Home",
        "products.php" => "Products",
        "orders.php" => "Pending Orders",
        "pending_trades.php" => "Pending Trades"
    ];
    private $actions = [
        "products.php" => "View and Modify Products",
        "orders.php" => "Pending Orders",
        "pending_trades.php" => "Pending Trade"
    ];

    public function __construct() {
        include 'connection.php';
        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: ../index.php");
            exit();
        }
    }

    public function renderHeader() {
        echo '<div class="header">
                <div class="logo">
                    <a href="../index.php"><img src="../images/PoCaSwap Logo.png" alt="Logo"></a>
                    <div class="title"><span>' . $this->title . '</span></div>
                </div>
                <div class="nav">';
        foreach ($this->navLinks as $link => $text) {
            echo '<a href="' . $link . '">' . $text . '</a>';
        }
        echo '</div></div>';
    }

    public function renderMain() {
        echo '<div class="main-container">
                <div class="welcome">
                    <h1>Welcome Admin!</h1>
                    <p>What do you want to do today?</p>
                </div>
                <div class="options">';
        
        foreach ($this->actions as $link => $text) {
            $buttonClass = '';
            if ($link === 'products.php') {
                $buttonClass = 'btn-products';
            } elseif ($link === 'orders.php') {
                $buttonClass = 'btn-orders';
            } elseif ($link === 'pending_trades.php') {
                $buttonClass = 'btn-trades';
            }
    
            echo '<a href="' . $link . '" class="button ' . $buttonClass . '">' . $text . '</a>';
        }
    
        echo '</div></div>';
    }    
}

$dashboard = new AdminDashboard();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../images/PoCaSwap Logo.ico"/>
    <title>Admin Dashboard</title>
</head>
<body>
    <?php 
    $dashboard->renderHeader();
    $dashboard->renderMain();
    ?>
</body>
</html>
