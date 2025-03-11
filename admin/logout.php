<?php
session_start();
// session_destroy();

// Xóa các session liên quan đến thông tin người dùng
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_role']);

header("Location: login.php");
exit();
?>