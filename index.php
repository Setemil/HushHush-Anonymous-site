<?php
// Simple index.php that acts as a router
session_start();

// First, check if this is a direct access to index.php
if (isset($_GET['username'])) {
    // If username is explicitly provided in GET parameters
    $username = $_GET['username'];
    header("Location: messages.php?to=" . urlencode($username));
    exit;
}

// Check if this is a pretty URL format (capturing username from path)
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/HushHush/'; // Update this to match your actual base path

// Extract username from the URL
if (strpos($requestUri, $basePath) === 0) {
    $pathAfterBase = substr($requestUri, strlen($basePath));
    $pathParts = explode('/', $pathAfterBase);
    
    // First part after base path should be username
    if (!empty($pathParts[0])) {
        $username = urldecode($pathParts[0]);
        
        // Check if the username exists in your database
        include 'conn.php';
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Username exists, redirect to send message
            header("Location: messages.php?to=" . urlencode($username));
            exit;
        }
    }
}

// If we reach here, either no username was found or it's invalid
// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to dashboard
    header("Location: dashboard.php");
    exit;
} else {
    // User is not logged in, redirect to login
    header("Location: login.php");
    exit;
}
?>