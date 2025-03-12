<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// $username = $_SESSION['user_name']; 
// $link = "http://localhost/HushHush/" . urlencode($username);


// Replace your existing link generation code with this:
$username = $_SESSION['user_name']; // or fetch from DB if needed

// Option 1: Direct link to send_message.php (most reliable)
$link = "http://localhost/HushHush/messages.php?to=" . urlencode($username);

// Option 2: If you still want to use the pretty URL (requires working .htaccess)
// $link = "http://localhost/HushHush/" . urlencode($username);

include 'conn.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM messages WHERE user_id = ? ORDER BY timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HushHush - Your Messages</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-left">
            <img src="#" alt="logo" class="logo">
            <span>Hush Hush</span>
        </div>
        <div class="header-right">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    
    <div class="dashboard-container">
        <section class="share-section">
            <h1 class="dashboard-header">Hi, <?php echo htmlspecialchars($username); ?></h1>
            <p class="share-description">Copy and share this link with your friends to receive anonymous messages</p>

            <div class="link-container">
                <input type="text" id="share-link" value="<?php echo $link; ?>" readonly>
                <button id="copy-btn" onclick="copyLink()">Copy</button>
            </div>

            <div class="share-options">
                <p>Or share directly:</p>
                <div class="social-icons">
                    <a href="https://wa.me/?text=<?php echo urlencode('Send me an anonymous message here: ' . $link); ?>" target="_blank" class="social-icon" title="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" onclick="alert('Copy the link and paste it manually on Snapchat/Instagram'); return false;" class="social-icon" title="Share on Snapchat"><i class="fab fa-snapchat"></i></a>
                    <a href="#" onclick="alert('Copy the link and paste it manually on Instagram'); return false;" class="social-icon" title="Share on Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </section>

        
        <section class="messages-section">
            <h2 class="messages-header">Your Messages</h2>
            
            <div class="messages-container">
                <?php
                if (empty($messages)) {
                    echo '<div class="empty-messages">
                            <p>No messages yet. Share your link to start receiving anonymous messages!</p>
                          </div>';
                } else {
                    foreach ($messages as $message) {
                        echo '<div class="message-card">
                                <p class="message-content">' . htmlspecialchars($message['content']) . '</p>
                                <div class="message-meta">
                                    <span class="message-time">' . date('M d, Y h:i A', strtotime($message['timestamp'])) . '</span>
                                </div>
                              </div>';
                    }
                }
                ?>
            </div>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>