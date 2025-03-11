<?php
    include "../config/connect.php";

    session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $color = $_POST['selected_color'];
    $size = $_POST['selected_size'];
    $quantity = $_POST['quantity'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id && $item['color'] == $color && $item['size'] == $size) {
            // Nếu sản phẩm đã tồn tại, cập nhật số lượng
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // Nếu sản phẩm chưa có, thêm vào giỏ hàng
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'color' => $color,
            'size' => $size,
            'quantity' => $quantity,
            'name' => $name,
            'price' => $price
        ];
    }

    // Chuyển hướng đến trang giỏ hàng
    header("Location: ../index.php?page_layout=cartguest");
    exit();
}

?>