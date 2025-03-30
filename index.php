

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=smart_toy" />
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_upward" /> -->
    <title>Rhodi shop</title>
</head>
<body>
    <?php include "user/header.php" ?>
    <div id="chat-button" onclick="toggleChat()">
        💬
    </div>

    <!-- Hộp chat -->
    <div id="chatbox">
        <div id="chat-header">
            <span class="chatbot-logo material-symbols-rounded">smart_toy</span>
            <p> ChatBot </p>
            <button class="close-chatbot" onclick="toggleChat()">✖</button>
        </div>
        <div id="chat-messages">
            <div class="message chatbot">
                <span class="bot-avatar material-symbols-rounded">smart_toy</span>
                <div class="chat-text"> Xin chào, tôi có thể giúp được gì cho bạn?</div>
            </div>
        </div>
        <div id="chat-input">
            <input type="text" id="user-input" placeholder="Nhập tin nhắn...">
            <button onclick="sendMessage()">
                <img src="assets/image/arrow.png" alt="Gửi"></img>
            </button>
        </div>
    </div>
</body>
<script src='assets/js/main.js'></script>
</html>