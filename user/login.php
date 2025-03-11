<?php
// session_start();

include 'config/connect.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // die("Email không hợp lệ!");
            $_SESSION['error'] = "Tên đăng nhập sai!";
            header('Location: index.php?page_layout=login'); // Quay lại trang đăng nhập
            exit();
        }

        // Truy vấn kiểm tra thông tin đăng nhập
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu
            if ($password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['phone'] = $user['phone'];
                header("Location: index.php?page_layout=main"); // Chuyển hướng sau khi đăng nhập thành công
                exit();
            } else {
                echo "Mật khẩu không đúng!";
            }
        } else {
            echo "Tài khoản không tồn tại!";
        }
    } else {
        echo "Vui lòng nhập đầy đủ thông tin!";
    }
}

session_write_close();
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
    <div class="login-container">
            <div class="left-section">
                <h1>ĐĂNG NHẬP</h1>
                <hr>
            </div>
            <form method="POST" action="" class="right-section">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit">ĐĂNG NHẬP</button>
                <a href="#">Quên mật khẩu?</a>
                <a href="index.php?page_layout=signin">Đăng ký</a>
            </form>
        </div>
        <?php include "footer.php" ?>
</body>