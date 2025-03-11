<?php
    include "../config/connect.php";
    session_start();
    if (!isset($_SESSION['user_name'])) {
        header("Location: ../index.php?page_layout=login");
        exit();
    }

    $sql = "SELECT c.id, p.name, p.price, c.quantity, c.size, c.color, pi.image 
        FROM cart c 
        JOIN product p ON c.product_id = p.id 
        LEFT JOIN product_inventory pi ON pi.product_id = p.id 
        WHERE c.product_id = p.id AND c.color = pi.color";

    $result = $conn->query($sql);
    $tong = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn giao hàng</title>
    <link rel="stylesheet" href="../assets/css/bill.css">
</head>
<body>
<div class="bill-container">
    <form action="payment.php" class="container" method="POST">
        <h2>Thông tin giao hàng</h2>
        <div class="form-group">
            <input type="text" id="name" name="name" placeholder="Họ và tên" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" id="phone" name="phone" placeholder="Số điện thoại" value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" id="address" name="address" placeholder="Địa chỉ" required>
        </div>
        <div class="form-group">
            <textarea id="note" name="note" rows="4" placeholder="Ghi chú"></textarea>
        </div>

        <div class="payment-method">
            <h2>Phương thức thanh toán</h2>
            <label>
                <input type="radio" name="payment" value="COD" checked> Thanh toán khi nhận hàng (COD)
            </label><br>
            <label>
                <input type="radio" name="payment" value="BANK TRANSFER"> Thanh toán chuyển khoản
            </label>
        </div>

        <div class="complete-order">
            <a href="../index.php?page_layout=cart">< Quay lại giỏ hàng</a>
            <button type="submit" class="btn-danger">Hoàn tất đơn hàng</button>
        </div>
    </form>
    <div class="item-container">
        <h2>Giỏ hàng</h2>
        <table>
            <?php
                $total_invoice = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_price = $row["price"] * $row["quantity"];
                        $tong++;
                        echo "<tr>";
                        echo "<td><img src='../assets/uploads/" . $row["image"] . "' width='50'></td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . number_format($row["price"], 0, ',', '.') . " đ</td>";
                        echo "<td>" . $row["quantity"] . "</td>";
                        // echo "<td>
                        // <button type='button' class='quantity-btn' onclick='changeQuantity(-1)'>−</button>
                        // <input type='text' name='quantity' class='quantity-input' id='quantity' value='1' readonly>
                        // <button type='button' class='quantity-btn' onclick='changeQuantity(1)'>+</button>
                        // </td>";
                        echo "<td>" . $row["size"] . "</td>";
                        echo "<td>" . $row["color"] . "</td>";
                        echo "<td><strong>" . number_format($total_price, 0, ',', '.') . " đ</strong></td>";
                        echo "</tr>";
                        $total_invoice = $total_invoice + $total_price;
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có sản phẩm trong giỏ hàng</td></tr>";
                }
            ?>
        </table>
        <div class="total">
            <span class="label">Tạm tính :</span>
            <span class="amount" id="subtotal"><?= number_format($total_invoice, 0, ',', '.') ?> đ</span><br>
            <span class="label">Phí vận chuyển :</span>
            <span class="amount">0 đ</span>
        </div>
        <div class="total">
            <span class="label">Tổng cộng :</span>
            <span class="amount" id="total"><strong><?= number_format($total_invoice, 0, ',', '.') ?> đ</strong></span>
        </div>
        <div class="note">
            <p>Chúng tôi sẽ XÁC NHẬN đơn hàng bằng EMAIL hoặc ĐIỆN THOẠI. Bạn vui lòng kiểm tra EMAIL hoặc NGHE MÁY ngay khi đặt hàng thành công và CHỜ NHẬN HÀNG.</p>
        </div>
    </div>
</div>
</body>
</html>