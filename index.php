

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Rhodi shop</title>
</head>
<body>
    <?php include "user/header.php" ?>
    <div id="chat-button" onclick="toggleChat()">
        💬
    </div>

    <!-- Hộp chat -->
    <div id="chatbox">
        <div id="chat-header" onclick="toggleChat()">Chat Hỗ Trợ ✖</div>
        <div id="chat-messages"></div>
        <div id="chat-input">
            <input type="text" id="user-input" placeholder="Nhập tin nhắn...">
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>
</body>
<script src='assets/js/main.js'></script>
</html>