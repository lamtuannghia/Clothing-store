<?php
    include '../config/connect.php';
    session_start();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $color = $_POST['selected_color'];
        $size = $_POST['selected_size'];
        $quantity = $_POST['quantity'];
        $user_id = $_SESSION['user_id'] ?? NULL; // Dành cho người dùng đăng nhập
    
        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng, thì cập nhật số lượng
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE product_id = ? AND color = ? AND size = ?");
        $stmt->bind_param("iss", $product_id, $color, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Sản phẩm đã tồn tại, cập nhật số lượng
            $new_quantity = $row['quantity'] + $quantity;
            $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update_stmt->bind_param("ii", $new_quantity, $row['id']);
            $update_stmt->execute();
        } else {
            // Chưa có trong giỏ, thêm mới
            $insert_stmt = $conn->prepare("INSERT INTO cart ( product_id, color, size, quantity) VALUES ( ?, ?, ?, ?)");
            $insert_stmt->bind_param("issi",  $product_id, $color, $size, $quantity);
            $insert_stmt->execute();
        }
    
        $_SESSION['success'] = "Thêm sản phẩm thành công!";
        // header("Location:../index.php?page_layout=cart"); // Chuyển hướng đến trang giỏ hàng
        header("Location:../index.php?page_layout=detail&id=".$product_id); // Chuyển hướng đến trang giỏ hàng
        exit(); 
    }
    
?>