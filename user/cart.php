<?php
    include "config/connect.php";

    $sql = "SELECT c.id, p.name, p.price, c.quantity, c.size, c.color, pi.image, p.id as ProId
        FROM cart c 
        JOIN product p ON c.product_id = p.id 
        LEFT JOIN product_inventory pi ON pi.product_id = p.id 
        WHERE c.product_id = p.id AND c.color = pi.color";

    $result = $conn->query($sql);
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
<div class="content cart-container mt-4">
    <div class="cart-item">
        <h2 class="text-center">GIỎ HÀNG</h2>    
        <table>
            <?php
                $total_invoice = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $total_price = $row["price"] * $row["quantity"];
                        echo "<tr>";
                        echo "<td><img src='assets/uploads/" . $row["image"] . "' width='50'></td>";
                        echo "<td><a href='index.php?page_layout=detail&id=" . $row['ProId'] . "'>" . $row["name"] . "</a></td>";
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
                        echo '<td> 
                        <a href="user/deletecart.php?id='. $row["id"] .'" class="btn btn-sm btn-outline-danger" onclick="return confirm(';
                        echo "'Bạn có chắc chắn muốn xóa?'";
                        echo ');">🗑️</a>
                        </td>';
                        echo "</tr>";
                        $total_invoice = $total_invoice + $total_price;
                        $isDisabled = false;
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có sản phẩm trong giỏ hàng</td></tr>";
                    $isDisabled = true;
                }
            ?>
        </table>
        <div class="note">
            <a href="index.php?page_layout=product">< Tiếp tục mua hàng</a>
        </div>
    </div>
    
    <form action="user/bill.php" class="detail-order">
        <h5>Thông tin đơn hàng</h5>
        <p>Tạm tính: <strong><?= number_format($total_invoice, 0, ',', '.') ?> đ</strong></p>
        <p>Tổng tiền: <strong><?= number_format($total_invoice, 0, ',', '.') ?> đ</strong></p>
        <button <?php echo $isDisabled ? 'disabled' : ''; ?> class="btn btn-danger w-100">THANH TOÁN</button>
    </form>
</div>
<?php 
    include "footer.php";
?>