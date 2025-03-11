<?php
    include "../config/connect.php";

    $id = $_GET['id'];

    $sql = "SELECT p.name, p.price, o.color, pi.image, o.size, o.quantity
            FROM orders o
            JOIN product_inventory pi ON o.product_id = pi.product_id
            JOIN product p ON o.product_id = p.id
            WHERE o.bill_id = '$id' AND o.color = pi.color";

    $result = mysqli_query($conn, $sql);

    $details = []; // Khởi tạo mảng để lưu dữ liệu từ `bill`

    // Lặp qua kết quả và lưu vào mảng `$orders`
    while ($row = mysqli_fetch_assoc($result)) {
        $details[] = $row;
    }
    $stt = 1;

    $sql_note = "SELECT note
            FROM bill
            WHERE id = '$id'";

    $result_note = mysqli_query($conn, $sql_note);
    $note = $result_note->fetch_assoc();
?>

<!-- Content -->
<div class="content">
    <h2>Details Orders</h2>
    <!-- <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-danger">Delete All Orders</button>
        <input type="text" id="search" class="form-control w-25" placeholder="Search Orders">
    </div> -->

    <!-- Bảng danh sách đơn hàng -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Color</th>
                <th>Size</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody id="orderList">
            <?php foreach ($details as $detail): ?>
            <tr>
                <td><?= $stt++ ?></td>
                <td><img src="../assets/uploads/<?= $detail['image']?>" width="60"></td>
                <td><?= $detail['name'] ?></td>
                <td><?= number_format($detail["price"], 0, ',', '.') ?></td>
                <td><?= $detail['color'] ?></td>
                <td><?= $detail['size'] ?></td>
                <td><?= $detail['quantity'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <strong>Ghi chú:</strong>
        <?php echo htmlspecialchars($note['note'])?>
    </p>
</div>

<script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script>
