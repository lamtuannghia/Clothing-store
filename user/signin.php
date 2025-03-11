<?php
    include "config/connect.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];

        // Kiểm tra email có tồn tại không để tránh trùng lặp
        $check_stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div style='padding: 10px;background-color: #f8d7da; color: red; border: 1px solid #f5c6cb; text-align: center;border-radius: 5px; width: 300px; margin: 50px auto;'>Email đã tồn tại! Vui lòng dùng email khác.</div>";
            header("refresh:2;url=index.php?page_layout=signin");
            exit();
        }
    
        $insert_stmt = $conn->prepare("INSERT INTO user( full_name, email, password, phone)VALUES (?,?,?,?)");
        $insert_stmt->bind_param("ssss",  $name, $email, $password, $phone);

        if ($insert_stmt->execute()) {
            echo "<div style='padding: 10px; background-color: #d4edda;color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; text-align: center; width: 300px; margin: 50px auto;'>
                    <strong>Chúc mừng!</strong> Bạn đã đăng ký thành công.
                  </div>";
            header("refresh:1;url=index.php?page_layout=login");
            exit();
        } else {
            echo "<div style='padding: 10px; background-color: #f8d7da; color: red; border: 1px solid #f5c6cb; text-align: center;border-radius: 5px; width: 300px; margin: 50px auto;'>Lỗi khi đăng ký. Vui lòng thử lại!</div>";
            header("refresh:2;url=index.php?page_layout=signin");
        }
    }
?>
<head>
    <?php
    if (isset($_GET['page_layout'])) {
        $page_css = "assets/css/" . $_GET['page_layout'] . ".css";
        if (file_exists($page_css)) {
            echo '<link rel="stylesheet" href="' . $page_css . '">';
        }
    }
    ?>
</head>
<body>
    <div class="register-container">
        <div class="left-section">
            <h1>ĐĂNG KÝ</h1>
            <hr>
        </div>
        <form action="" method="POST" class="right-section">
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="text" name="phone" placeholder="Điện thoại" required>
            <button type="submit">ĐĂNG KÝ</button>
            <a href="./index.php">Quay lại trang chủ</a>
        </form>
    </div>
    <?php include "footer.php" ?>
</body>