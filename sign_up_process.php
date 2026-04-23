<?php
include 'admin/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim all inputs
    $username = trim($_POST['username'] ?? '');
    $firstname = trim($_POST['Firstname'] ?? '');
    $lastname = trim($_POST['Lastname'] ?? '');
    $phone_num = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Check for empty fields
    if (empty($username) || empty($firstname) || empty($lastname) || empty($phone_num) || empty($password)) {
        die("All fields are required.");
    }

    // Check if username already exists
    $stmt = $con->prepare("SELECT username FROM info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Username already taken. Choose another one.");
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $con->prepare("INSERT INTO info (username, firstname, lastname, phonenumber, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $firstname, $lastname, $phone_num, $hashed_password);

    if ($stmt->execute()) {
        echo "Sign-up successful! Redirecting...";
        header("Refresh:2; url=login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    // If accessed directly without POST
    header("Location: sign_up.php");
    exit();
}
?>
