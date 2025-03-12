<?php
include 'conn.php';

$error = "";
$success = "";
$recipient = "";

// Check if recipient username is provided
if (!isset($_GET['to']) || empty($_GET['to'])) {
    header("Location: login.php");
    exit;
}

$recipient = $_GET['to'];

// Verify the recipient exists in your database
$check_user = $conn->prepare("SELECT id FROM users WHERE name = ?");
$check_user->bind_param("s", $recipient);
$check_user->execute();
$result = $check_user->get_result();

// If user doesn't exist, redirect to homepage
if ($result->num_rows == 0) {
    header("Location: login.php");
    exit;
}

$recipient_data = $result->fetch_assoc();
$recipient_id = $recipient_data['id'];

// Process message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    
    if (empty($message)) {
        $error = "Message cannot be empty";
    } else {
        // Insert message into the database
        $stmt = $conn->prepare("INSERT INTO messages (user_id, content, timestamp) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $recipient_id, $message);
        
        if ($stmt->execute()) {
            $success = "Message sent successfully!";
        } else {
            $error = "Failed to send message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Anonymous Message to <?php echo htmlspecialchars($recipient); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Send Anonymous Message to <?php echo htmlspecialchars($recipient); ?></h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success">
                <?php echo $success; ?>
                <p>Send another message or create your own link <a href="main.php">here.</a></p>
            </div>
        <?php endif; ?>
        
        <form class="message-form" method="POST" action="">
            <textarea name="message" placeholder="Type your anonymous message here..."></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>