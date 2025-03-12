<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user_name'];
$link = "http://localhost/HushHush/" . urlencode($username);

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
    <style>
          /* Modal overlay styles */
          .modal-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.85);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in;
        }
        
        .modal-box {
            background-color: #1e1e1e;
            color: #fff;
            padding: 2rem;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
            animation: slideUp 0.4s ease-in-out;
        }
        
        .modal-box h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, #ff66c4, #cb6ce6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .modal-box ul {
            text-align: left;
            margin: 1rem 0;
            padding-left: 1rem;
            color: #ccc;
        }
        
        .modal-box ul li {
            margin-bottom: 0.6rem;
        }
        
        .close-btn {
            background-color: #cb6ce6;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            margin-top: 1rem;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        
        .close-btn:hover {
            background-color: #b453d6;
        }
        
        @keyframes slideUp {
            from {
            transform: translateY(40px);
            opacity: 0;
            }
            to {
            transform: translateY(0px);
            opacity: 1;
            }
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo {
            height: 40px;
            width: auto;
        }
        
        .header-left span {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #ff66c4, #cb6ce6);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="HushHush.jpg" alt="logo" class="logo">
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
                    <a href="#" onclick="shareSnapchatGuide(); return false;" class="social-icon" title="Share on Snapchat"><i class="fab fa-snapchat"></i></a>
                    <a href="#" onclick="shareInstagramGuide(); return false;" class="social-icon" title="Share on Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </section>

        <section class="messages-section">
            <h2 class="messages-header">Your Messages</h2>
            <div class="messages-container">
                <?php
                if (empty($messages)) {
                    echo '<div class="empty-messages"><p>No messages yet. Share your link to start receiving anonymous messages!</p></div>';
                } else {
                    foreach ($messages as $message) {
                        $msgContent = htmlspecialchars($message['content'], ENT_QUOTES);
                        $msgTime = date('M d, Y h:i A', strtotime($message['timestamp']));
                        $msgId = $message['id'];

                        echo '<div class="message-card" onclick="openMessageModal(' . $msgId . ', \'' . $msgContent . '\', \'' . $msgTime . '\')">
                                <p class="message-content">' . $msgContent . '</p>
                                <div class="message-meta"><span class="message-time">' . $msgTime . '</span></div>
                              </div>';
                    }
                }
                ?>
            </div>
        </section>

        <!-- Instagram Sharing Guide Modal -->
        <div id="instagram-guide" class="modal-overlay">
            <div class="modal-box">
                <h2><i class="fab fa-instagram"></i> Share on Instagram Story</h2>
                <p>Your link has been copied to clipboard!</p>
                <ul>
                    <li>1️⃣ Open Instagram App</li>
                    <li>2️⃣ Tap <strong>+</strong> to create a new story</li>
                    <li>3️⃣ Add a <strong>Link Sticker</strong></li>
                    <li>4️⃣ Paste the copied link</li>
                    <li>5️⃣ Share your story 🎉</li>
                </ul>
                <button onclick="closeGuide()" class="close-btn">Got it!</button>
            </div>
        </div>

        <!-- Snapchat Sharing Guide Modal -->
        <div id="snapchat-guide" class="modal-overlay">
            <div class="modal-box">
                <h2><i class="fab fa-snapchat"></i> Share on Snapchat</h2>
                <p>Your link has been copied to clipboard!</p>
                <ul>
                    <li>1️⃣ Open Snapchat</li>
                    <li>2️⃣ Tap the camera to create a new Snap</li>
                    <li>3️⃣ Tap the <strong>📎 Paperclip icon</strong></li>
                    <li>4️⃣ Paste the link</li>
                    <li>5️⃣ Share your Snap!</li>
                </ul>
                <button onclick="closeSnapchatGuide()" class="close-btn">Got it!</button>
            </div>
        </div>
    </div>

    <script>
        function copyLink() {
            const linkInput = document.getElementById('share-link');
            const link = linkInput.value;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(link).then(() => {
                    showCopiedFeedback();
                });
            } else {
                linkInput.select();
                document.execCommand('copy');
                showCopiedFeedback();
            }
        }

        function showCopiedFeedback() {
            const copyBtn = document.getElementById('copy-btn');
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            setTimeout(() => {
                copyBtn.textContent = originalText;
            }, 2000);
        }

        function shareInstagramGuide() {
            copyLink();
            document.getElementById("instagram-guide").style.display = "flex";
        }

        function closeGuide() {
            document.getElementById("instagram-guide").style.display = "none";
        }

        function shareSnapchatGuide() {
            copyLink();
            document.getElementById("snapchat-guide").style.display = "flex";
        }

        function closeSnapchatGuide() {
            document.getElementById("snapchat-guide").style.display = "none";
        }
    </script>
</body>
</html>
