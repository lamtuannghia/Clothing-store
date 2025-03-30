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

    // Kiểm tra nếu người dùng hỏi về giá sản phẩm
    if (strpos($message, "giá") !== false || strpos($message, "sản phẩm") !== false) {
        $api_url = "http://localhost/ttap2/data/export_product.php"; // Thay đổi thành URL API của bạn
        $products_data = file_get_contents($api_url);
        $products = json_decode($products_data, true);

        if ($products) {
            $response = "Dưới đây là một số sản phẩm:\n";
            foreach (array_slice($products, 0, 3) as $product) { // Hiển thị 3 sản phẩm đầu tiên
                $response .= "- {$product['name']} ({$product['category']}): {$product['price']} VNĐ\n";
            }
            $response .= "Bạn muốn biết thêm sản phẩm nào không?";
        } else {
            $response = "Xin lỗi, tôi không thể lấy dữ liệu sản phẩm ngay bây giờ.";
        }
    }

    echo $response;
}
?>
