<?php
    include ("config/connect.php");

    // Lấy thông tin user từ session
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];

    // Lấy danh sách đơn hàng từ database
    $stmt = $conn->prepare("
                    SELECT 
                        b.id, 
                        b.status, 
                        b.time_create, 
                        SUM(o.quantity * p.price) AS total_price,
                        GROUP_CONCAT(p.name ORDER BY o.product_id SEPARATOR ' | ') AS product_names,
                        GROUP_CONCAT(pi.image ORDER BY o.product_id SEPARATOR ' | ') AS product_images,
                        GROUP_CONCAT(o.quantity ORDER BY o.product_id SEPARATOR ' | ') AS quantities,
                        GROUP_CONCAT(o.color ORDER BY o.product_id SEPARATOR ' | ') AS colors,
                        GROUP_CONCAT(o.size ORDER BY o.product_id SEPARATOR ' | ') AS sizes
                    FROM bill b
                    JOIN orders o ON b.id = o.bill_id
                    LEFT JOIN product p ON o.product_id = p.id
                    LEFT JOIN product_inventory pi ON o.product_id = pi.product_id AND o.color = pi.color
                    WHERE b.user_id = ?
                    GROUP BY b.id
                    ORDER BY b.time_create DESC;
                ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();
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
<div class="content account-container">
    <div class="order-list">
        <h2>Đơn hàng của bạn</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Trạng thái</th>
                <th>Ngày đặt hàng</th>
                <th>Sản phẩm</th>
                <th>Tổng tiền</th>
            </tr>
            <?php while ($order = $orders->fetch_assoc()) : ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($order['time_create'])); ?></td>
                <td>
                    <?php
                    $product_names = explode(" | ", $order['product_names']);
                    $product_images = explode(" | ", $order['product_images']);
                    $quantities = explode(" | ", $order['quantities']);
                    $colors = explode(" | ", $order['colors']);
                    $sizes = explode(" | ", $order['sizes']);

                    for ($i = 0; $i < count($product_names); $i++) {
                        echo "<li>";
                        echo "<img src='assets/uploads/" . $product_images[$i] . "' width='50' height='50'> ";
                        echo "<b>" . $product_names[$i] . "</b>";
                        echo " - " . $quantities[$i];
                        echo " - " . $colors[$i];
                        echo " - " . $sizes[$i];
                        echo "</li>";
                    }
                    ?>
                </td>
                <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?> VNĐ</td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="profile-sidebar">
        <h2>Thông tin cá nhân</h2>
        <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($user_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <a href="edit_profile.php" class="btn w-50">Sửa thông tin</a>
        <a href="user/logout.php" class="btn btn-danger w-50" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">Đăng xuất</a>
    </div>
</div>
<?php
    include ("footer.php");
?>