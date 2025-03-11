<?php
    include "../config/connect.php";
    session_start();
    $user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $note = $_POST['note'];
        $payment = $_POST['payment'];
    
        $conn->begin_transaction(); // Bắt đầu transaction

        try {
        $stmt = $conn->prepare("INSERT INTO bill (user_id, address, full_name, email, phone, note, payment) 
                                        VALUE (?,?,?,?,?,?,?)");
        $stmt->bind_param("issssss",$user_id,$address,$name,$email,$phone,$note,$payment);
        $stmt->execute();
        

        $bill_id = $conn->insert_id; // Lấy bill_id vừa tạo

        // 2️⃣ Lấy dữ liệu từ bảng `product`
        $stmt = $conn->prepare("
            SELECT product_id, color, size, quantity
            FROM cart
        ");
        $stmt->execute();
        $result = $stmt->get_result();

        // 3️⃣ Chèn dữ liệu vào bảng `orders` kèm theo `bill_id`
        $insertStmt = $conn->prepare("
            INSERT INTO orders (bill_id, product_id, quantity, color, size) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $updateStmt = $conn->prepare("
            UPDATE product_inventory pi
            JOIN size s ON pi.id = s.inven_id
            SET s.quantity = s.quantity - ? 
            WHERE pi.product_id = ? AND pi.color = ? AND s.size = ?
        ");

        while ($row = $result->fetch_assoc()) {
            $insertStmt->bind_param("iiiss", $bill_id, $row['product_id'], $row['quantity'], $row['color'], $row['size']);
            $insertStmt->execute();

            $updateStmt->bind_param("iiss", $row['quantity'], $row['product_id'], $row['color'], $row['size']);
            $updateStmt->execute();
        }

        // 4️⃣ Xóa dữ liệu khỏi bảng `product` sau khi đã chuyển sang `orders`
        $conn->query("DELETE FROM cart");

        // 5️⃣ Xác nhận transaction
        $conn->commit();
        echo "Dữ liệu đã chuyển sang orders với bill_id = $bill_id và xóa khỏi product.";

        header("Location:../index.php?page_layout=main"); // Chuyển hướng đến trang giỏ hàng
        exit();
        
        } catch (Exception $e) {
            $conn->rollback(); // Nếu có lỗi, hủy bỏ transaction
            echo "Lỗi: " . $e->getMessage();
        }
    }

    
?>