let lastScrollTop = 0;
const header = document.getElementById('myDiv');
const nav = document.getElementById('myNav');

window.addEventListener('scroll', function() {
if (window.scrollY === 0) {
    // Khi cuộn lên đầu trang, hiện thẻ div
    nav.style.position = "relative";
    header.style.display = "block";
} else {
    // Khi cuộn xuống, ẩn thẻ div
    nav.style.position = "fixed";
    header.style.display = "none";
}
});

// chatbot
function toggleChat() {
    let chatbox = document.getElementById("chatbox");
    let chatButton = document.getElementById("chat-button");

    if (chatbox.style.display === "none" || chatbox.style.display === "") {
        chatbox.style.display = "block";
        chatButton.style.display = "none"; // Ẩn nút tròn khi mở chat
    } else {
        chatbox.style.display = "none";
        chatButton.style.display = "block"; // Hiện lại nút tròn khi đóng chat
    }
}
document.getElementById("user-input").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Ngăn form submit mặc định
        sendMessage(); // Gọi hàm gửi tin nhắn
    }
});

function createMessageElement(message, sender) {
    let messageDiv = document.createElement("div");
    messageDiv.classList.add("message", sender);

    let chatText = document.createElement("div");
    chatText.classList.add("chat-text");
    chatText.textContent = message;

    if (sender === "chatbot") {
        let botAvatar = document.createElement("span");
        botAvatar.classList.add("bot-avatar", "material-symbols-rounded");
        botAvatar.textContent = "smart_toy";
        messageDiv.appendChild(botAvatar);
    }

    messageDiv.appendChild(chatText);
    return messageDiv;
}

function sendMessage() {
    let inputField = document.getElementById("user-input");
    let userMessage = inputField.value.trim();
    
    if (userMessage === "") return;

    let chatMessages = document.getElementById("chat-messages");
    let userMessageDiv = createMessageElement(userMessage, "userMessage");
    chatMessages.appendChild(userMessageDiv);

    // Gửi dữ liệu đến server
    fetch("user/chatbot.php", {
        method: "POST",
        body: new URLSearchParams({ message: userMessage }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
    .then(response => response.text())
    .then(data => {
        let botMessageDiv = createMessageElement(data, "chatbot");
        chatMessages.appendChild(botMessageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight; // Cuộn xuống tin nhắn mới
    });

    inputField.value = ""; // Xóa input sau khi gửi
}


