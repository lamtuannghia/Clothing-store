<?php

    $stmt = $conn->prepare("
                    SELECT 
                        b.id AS bill_id, 
                        b.user_id,
                        u.full_name AS user_name, 
                        u.email, 
                        u.phone,
                        b.address,
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
                    LEFT JOIN user u ON b.user_id = u.id
                    LEFT JOIN product p ON o.product_id = p.id
                    LEFT JOIN product_inventory pi ON o.product_id = pi.product_id AND o.color = pi.color
                    GROUP BY b.id
                    ORDER BY b.time_create DESC;
                ");
    $stmt->execute();
    $orders = $stmt->get_result();
?>
 
<!-- Content -->
<div class="content">
    <h2>Orders</h2>
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-danger">Delete All Orders</button>
        <input type="text" id="search" class="form-control w-25" placeholder="Search Orders">
    </div>

    <!-- Bảng danh sách đơn hàng -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Order Status</th>
                <th>Order Date</th>
                <th>Product</th>
                <th>Total Price</th>
                <th>Order Details</th>
            </tr>
        </thead>
        <tbody id="orderList">
            <?php while ($order = $orders->fetch_assoc()) : ?>
            <tr>
                <td>#<?php echo $order['bill_id']; ?></td>
                <td style="max-width: 180px">
                    <?php echo $order['user_name'];?><br>
                    <?php echo $order['email'];?><br>
                    <?php echo $order['phone'];?><br>
                    <?php echo $order['address'];?><br>
                </td>
                <td>
                    <p class="<?php 
                        if ($order['status'] == 'shipped') echo 'status-success';
                        elseif ($order['status'] == 'pending') echo 'status-pending';
                        elseif ($order['status'] == 'cancel') echo 'status-cancel';
                        else echo 'status-default';
                    ?>">
                    <?php echo $order['status']; ?></p>
                </td>
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
                        echo "<img src='../assets/uploads/" . $product_images[$i] . "' width='50' height='50'> ";
                        echo "<b>" . $product_names[$i] . "</b>";
                        echo " - " . $quantities[$i];
                        echo " - " . $colors[$i];
                        echo " - " . $sizes[$i];
                        echo "</li>";
                    }
                    ?>
                </td>
                <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?> VNĐ</td>
                <td>
                <a href="index.php?page_layout=detailorders&id=<?php echo $order['bill_id'];?>" class="btn btn-sm btn-outline-primary">Detail</a><br>
                <a href="orders_status.php?status=success&id=<?php echo $order['bill_id'];?>" class="btn btn-sm btn-outline-success">✔️ Xác Nhận</a><br>
                <a href="orders_status.php?status=cancel&id=<?php echo $order['bill_id'];?>" class="btn btn-sm btn-outline-danger">❌ Hủy</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- <script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script> -->
