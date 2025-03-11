<?php
    include "../config/connect.php";

    session_start();

    if( isset($_POST["dangnhap"])){
        $email = $_POST["email"];
        $password = $_POST["password"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // die("Email không hợp lệ!");
            $_SESSION['error'] = "Mật khẩu không đúng hoặc tên đăng nhập sai!";
            header('Location: login.php'); // Quay lại trang đăng nhập
            exit();
        }

        // Truy vấn kiểm tra tài khoản trong cơ sở dữ liệu
        $sql = "SELECT * FROM admin WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $password == $user['password']) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'];
            $_SESSION['admin_role'] = $user['role'];
            
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Mật khẩu không đúng hoặc tên đăng nhập sai!";
            header('Location: login.php'); // Quay lại trang đăng nhập
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/admin_login.css">
    <title>Rhodi Admin</title>
</head>
<body>
    <?php
    if (isset($_SESSION['error'])) {
        echo "
        <div class='alert'>
            <span>" . $_SESSION['error'] . "</span>
            <button class='close-btn' onclick='this.parentElement.style.display=\"none\";'>&times;</button>
        </div>";
        unset($_SESSION['error']); // Xóa lỗi sau khi hiển thị
    }
    ?>
    <form action="login.php" method="post" class="login-container">
        <div class="left-section">
            <div class="logo">
                <img src="../assets/image/logo.jpg" alt="Logo"/>
            </div>
            <h1>ĐĂNG NHẬP</h1>
            <hr>
        </div>
        <div class="right-section">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Mật khẩu">
            <button type="submit" name="dangnhap">ĐĂNG NHẬP</button>
            <a href="#">Quên mật khẩu?</a>
        </div>        
    </form> 
</body>
</html>
