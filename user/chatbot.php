<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = strtolower(trim($_POST["message"]));
    $response = "Xin lỗi, tôi chưa hiểu câu hỏi của bạn.";

    // Câu trả lời mẫu
    $responses = [
        "xin chào" => "Chào bạn! Tôi có thể giúp gì?",
        "bạn tên gì" => "Tôi là chatbot hỗ trợ khách hàng!",
        "giá sản phẩm" => "Bạn muốn hỏi giá sản phẩm nào?",
        "liên hệ" => "Bạn có thể liên hệ qua email support@website.com",
        "cảm ơn" => "Không có gì! Tôi luôn sẵn sàng giúp đỡ."
    ];

    // Tìm câu trả lời phù hợp
    foreach ($responses as $key => $value) {
        if (strpos($message, $key) !== false) {
            $response = $value;
            break;
        }
    }

    echo $response;
}
?>
