<?php
session_start();
// session_destroy();

// Xóa các session liên quan đến thông tin người dùng
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['email']);
unset($_SESSION['phone']);

header("Location: ../index.php");
exit();
?>