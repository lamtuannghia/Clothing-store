<?php
    include "config/connect.php";
    
    $user_id = $_SESSION['user_id']; // Lấy ID sản phẩm từ URL

    $stmt = $conn->prepare("
        SELECT * 
        FROM user
        WHERE id = ?
    ");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc()

?>
<head>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="register-container">
        <div class="left-section">
            <h1>THÔNG TIN NGƯỜI DÙNG</h1>
            <hr>
        </div>
        <form action="" method="POST" class="right-section">
            <input type="text" name="full_name" placeholder="Họ và tên" value="<?php echo $row['full_name']?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $row['email']?>" required>
            <input type="text" name="password" placeholder="Mật khẩu" value="<?php echo $row['password']?>" required>
            <input type="text" name="phone" placeholder="Điện thoại" value="<?php echo $row['phone']?>" required>
            <button type="submit" name="add_to_cart"  class="buy-btn">Cập nhật</button>
        </form>
    </div>
    <?php include "footer.php" ?>
</body>