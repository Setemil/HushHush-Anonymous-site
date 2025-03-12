<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if email already exists using prepared statement
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "Email already registered.";
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();

    // Hash password and insert user using prepared statement
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($insert_stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "Signup failed: " . $insert_stmt->error;
    }

    $insert_stmt->close();
}
?>
