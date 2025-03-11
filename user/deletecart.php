<?php
include ('../config/connect.php');
$id = $_GET['id']; // Lấy ID từ URL

// Xóa dữ liệu
$sql = "DELETE FROM cart WHERE id = '$id'";
$conn->query($sql);

// Chuyển hướng về trang danh sách
header("Location: ../index.php?page_layout=cart");
?>