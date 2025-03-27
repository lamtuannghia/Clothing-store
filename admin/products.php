<?php
include "../config/connect.php"; // Kết nối database

// Lấy danh sách sản phẩm từ database
$sql = "SELECT p.id, p.name as ProName, c.name, p.price, p.quantity , p.status
        FROM product p
        JOIN categories c ON p.category_id = c.id
        GROUP BY p.id
        ORDER BY p.id DESC"; 

$result = mysqli_query($conn, $sql);

$sql_quantity = "UPDATE product p
                JOIN (
                    SELECT pi.product_id, SUM(s.quantity) AS total_quantity
                    FROM size s
                    JOIN product_inventory pi ON s.inven_id = pi.id
                    GROUP BY pi.product_id
                ) t ON p.id = t.product_id
                SET p.quantity = t.total_quantity";

$result_size = mysqli_query($conn,$sql_quantity);

$stt = 1;
?>

<!-- Content -->
<div class="content">
    <h2>Products</h2>
    <div class="d-flex justify-content-between mb-3">
        <a class="btn btn-primary" href="index.php?page_layout=newproduct">New Product</a>
        <input type="text" id="search" class="form-control w-25" placeholder="Search Products">
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <!-- <th>Product Image</th> -->
                <th>Product Name</th>
                <th>Product Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="productList">
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <tr class="<?= $product['quantity'] == 0 ? 'sold-out' : '' ?>">
                <td><?= $stt++; ?></td>
                <!-- <td><img src="../assets/uploads/<?= $product['Image'] ?>" width="50"></td> -->
                <td>
                    <?= $product['ProName'] ?><br>
                    <small>Quantity: <?= $product['quantity'] ?></small>
                </td>
                <td><?= $product['name'] ?></td>
                <td><?= number_format($product['price'], 0, ',', '.') ?> VND</td>
                <td>
                    <span class="badge <?= $product['quantity'] == 0 ? 'bg-secondary' : 'bg-primary' ?>">
                        <?= $product['status'] ?>
                    </span>
                </td> 
                <td>
                    <a href="index.php?page_layout=detailproduct&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">🔍</a>
                    <?php if ($_SESSION['admin_role'] == 'admin') {?>
                        <a href="index.php?page_layout=editnewpro&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">✏️</a>
                        <a href="deleteproduct.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️</>
                    <?php }?>
                </td>  
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
