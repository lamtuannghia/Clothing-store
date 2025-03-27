<?php
include "../config/connect.php"; // Kết nối database

$id = $_GET['id'];

// Lấy danh sách sản phẩm từ database
$sql = "SELECT p.id ,p.color, p.image, GROUP_CONCAT(s.size, ' (', s.quantity, ') ' SEPARATOR ', ') AS size_quantity
        FROM product_inventory p
        JOIN size s ON p.id = s.inven_id
        WHERE p.product_id = '$id'
        GROUP BY s.inven_id"; // Sắp xếp theo ID mới nhất

$result = mysqli_query($conn, $sql);

$stt = 1;
?>

<!-- Content -->
<div class="content">
    <h2>Detail Products</h2>
    <div class="d-flex justify-content-between mb-3">
        <a class="btn btn-primary" href="index.php?page_layout=addproduct&id=<?= $id ?>">Add Product</a>
        <!-- <input type="text" id="search" class="form-control w-25" placeholder="Search Products"> -->
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Product Image</th>
                <th>Product Color</th>
                <th>Product Size & Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="productList">
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <tr class="<?= $product['size_quantity'] == 0 ? 'sold-out' : '' ?>">
                <td><?= $stt++; ?></td>
                <td><img src="../assets/uploads/<?= $product['image'] ?>" width="50"></td>
                <td><?= $product['color'] ?><br></td>
                <td><?= $product['size_quantity'] ?></td>
                <td>
                    <a href="index.php?page_layout=editproduct&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">✏️</a>
                    <?php if ($_SESSION['admin_role'] == 'admin') {?>
                        <a href="deletedetail.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️</a>
                    <?php }?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
