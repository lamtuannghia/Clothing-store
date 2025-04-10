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

        $api_url = "http://localhost/ttap2/data/export_product.php"; // Thay đổi thành URL API của bạn
        $products_data = file_get_contents($api_url);
        $products = json_decode($products_data, true);

        // Kiểm tra xem người dùng có hỏi về một sản phẩm cụ thể không
        foreach ($products as $product) {
            // Chuyển tên sản phẩm về chữ thường
            $product_name = strtolower($product["name"]);

            // Kiểm tra nếu tin nhắn của người dùng chứa một phần tên sản phẩm
            if (strpos($product_name, $message) !== false) {
                $response = "Sản phẩm: " . $product["name"] . "\n";
                $response .= "Giá: " . $product["price"] . " VNĐ\n";
                $response .= "Danh mục: " . $product["category"];
                break; // Dừng vòng lặp khi tìm thấy sản phẩm
            }
        }
        echo $response;
}
?>
