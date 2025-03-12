<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit;
    }

    // Query the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Incorrect password.";
            exit;
        }
    } else {
        echo "Account not found.";
        exit;
    }
} else {
    echo "Invalid request method.";
    exit;
}
?>
