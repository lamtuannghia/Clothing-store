<?php
session_start();
header('Content-Type: application/json'); // Đảm bảo trả về JSON

include ("../config/connect.php");

// Lấy user_id từ session (giả sử người dùng đã đăng nhập)
$user_id = $_SESSION['user_id'] ?? 0; // Nếu chưa đăng nhập, user_id = 0

// Truy vấn đếm số lượng sản phẩm trong giỏ hàng
$sql = "SELECT COUNT(*) AS total_items FROM cart ";
$stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Nếu có dữ liệu, lấy số lượng, nếu không thì = 0
$total_items = $row['total_items'] ?? 0;

echo json_encode(["count" => $total_items]); // Trả về JSON

$conn->close();
?>
