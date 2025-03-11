<?php
    include "config/connect.php";
    $tong = 0;
?>
<head>
<link rel="stylesheet" href="assets/css/cart.css">
</head>
<div class="content cart-container mt-4">
    <div class="cart-item">
        <h2 class="text-center">GIỎ HÀNG</h2>    
        <table>
            <?php
                $total_invoice = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $total_price = $item["price"] * $item["quantity"];
                        $tong++;
                        echo "<tr>";
                        echo "<td><img src='assets/uploads/" . $item["image"] . "' width='50'></td>";
                        echo "<td>" . $item["name"] . "</td>";
                        echo "<td>" . number_format($item["price"], 0, ',', '.') . " đ</td>";
                        echo "<td>" . $item["quantity"] . "</td>";
                        // echo "<td>
                        // <button type='button' class='quantity-btn' onclick='changeQuantity(-1)'>−</button>
                        // <input type='text' name='quantity' class='quantity-input' id='quantity' value='1' readonly>
                        // <button type='button' class='quantity-btn' onclick='changeQuantity(1)'>+</button>
                        // </td>";
                        echo "<td>" . $item["size"] . "</td>";
                        echo "<td>" . $item["color"] . "</td>";
                        echo "<td><strong>" . number_format($total_price, 0, ',', '.') . " đ</strong></td>";
                        echo "<td>
                        <a href='user/deletecart.php?id=". $item["id"] ."' class='btn btn-sm btn-outline-danger' onclick='return confirm('Bạn có chắc chắn muốn xóa?');'>🗑️</a>
                        </td>";
                        echo "</tr>";
                        $total_invoice = $total_invoice + $total_price;
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có sản phẩm trong giỏ hàng</td></tr>";
                }
            ?>
        </table>
        <div class="note">
            <label for="order-notes" class="form-label">Ghi chú đơn hàng</label>
            <textarea id="order-notes" class="form-control" rows="3"></textarea>
        </div>
    </div>
    
    <div class="detail-order">
        <h5>Thông tin đơn hàng</h5>
        <p>Tạm tính: <strong><?= number_format($total_invoice, 0, ',', '.') ?> đ</strong></p>
        <p>Tổng tiền: <strong><?= number_format($total_invoice, 0, ',', '.') ?> đ</strong></p>
        <button class="btn btn-danger w-100">THANH TOÁN</button>
    </div>
</div>
<?php 
    include "footer.php";
    echo json_encode(["count" => $tong]);
?>