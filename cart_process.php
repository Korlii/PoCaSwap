<?php
    include 'admin/connection.php';
    session_start();

    $user_id = $_SESSION['user_id'];

    if(isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        $stmt = $con->prepare("SELECT Quantity FROM products
                                WHERE Product_ID = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($stock);
        $stmt->fetch();
        $stmt->close();

        $stmt = $con->prepare("SELECT quantity FROM cart
                                WHERE User_ID = ? AND Product_ID = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
        $stmt->execute();
        $stmt->bind_result($cart_quantity);
        $stmt->fetch();
        $stmt->close();


        if ($cart_quantity === null) {
            if ($quantity > $stock){
                echo "<script>alert('No Stocks Available')</script>";
            } else {
                $stmt = $con->prepare("INSERT INTO cart (User_ID, Product_ID, quantity) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
                $stmt->execute();
                $stmt->close();
            }
        } else {

            $new_qty = min($cart_quantity + $quantity, $stock);

            if($new_qty > $cart_quantity) {
                $stmt = $con->prepare("INSERT INTO cart (User_ID ,Product_ID, quantity) VALUES (?, ?, ?)
                                            ON DUPLICATE KEY 
                                            UPDATE quantity = quantity + VALUES(quantity)");
                $stmt->bind_param('iii', $user_id,$product_id, $quantity);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<script>alert('Exceeds Available Stock in cart');
                    window.history.back();</script>";
                die();
            }
        }

        $con->close();
    }

    if (isset($_GET['add'])) {
        $product_id = intval($_GET['add']);
        $stmt = $con->prepare("SELECT c.Product_ID, c.quantity, p.Quantity 
                            FROM cart c
                            JOIN products p ON c.Product_ID = p.Product_ID
                            WHERE c.User_ID = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['quantity'] < $_GET['qty']) {
            header('Location: cart.php');
            exit();
        } else {
            $stmt = $con->prepare("UPDATE cart SET quantity = quantity + 1 WHERE User_ID = ? AND Product_ID = ?");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $stmt->close();
            header('Location: cart.php');
            exit();
        }
    }

    if (isset($_GET['minus'])) {
        $product_id = intval($_GET['minus']);
        $stmt = $con->prepare("UPDATE cart SET quantity = quantity - 1 WHERE User_ID = ? AND Product_ID = ? AND quantity > 1");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();

        // Quan = 0 remove item from cart
        $stmt = $con->prepare("DELETE FROM cart WHERE User_ID = ? AND Product_ID = ? AND quantity <= 0");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
        header('Location: cart.php');
        exit();
    }

    if (isset($_GET['remove'])) {
        $product_id = intval($_GET['remove']);
        $stmt = $con->prepare("DELETE FROM cart WHERE User_ID = ? AND Product_ID = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }


    if (isset($_GET['clear'])) {
        $stmt = $con->prepare("DELETE FROM cart WHERE User_ID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_GET['remove'])) {
        $product_id = intval($_GET['remove']);
        header('Location: cart.php');
        exit();
        }


    if (isset($_GET['clear'])) {
        $stmt = $con->prepare("DELETE FROM cart WHERE User_ID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['cart'] = 'empty';
        header('Location: cart.php');
        exit();
    }

    // Safe redirect: ensure $product_id is set and an integer, otherwise go to shop
    if (isset($product_id) && intval($product_id) > 0) {
        $pid = intval($product_id);
        header('Location: product.php?id=' . $pid);
    } else {
        header('Location: shop.php');
    }
    exit();
?>
