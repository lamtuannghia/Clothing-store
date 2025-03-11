<?php
include "../config/connect.php"; // Kết nối database

// Lấy danh sách sản phẩm từ database
$sql = "SELECT id, full_name, email, role, phone
        FROM admin "; 

$result = mysqli_query($conn, $sql);

$stt = 1;
?>

<!-- Content -->
<div class="content">
    <h2>Users</h2>
    <div class="d-flex justify-content-between mb-3">
        <a class="btn btn-primary" href="index.php?page_layout=newproduct">New Users</a>
        <input type="text" id="search" class="form-control w-25" placeholder="Search Products">
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="userList">
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $stt++; ?></td>
                <td><?= $user['full_name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['role'] ?></td>
                <td><?= $user['phone'] ?></td>
                <td>
                    <a href="index.php?page_layout=detailproduct&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">🔍</a>
                    <?php if ($_SESSION['admin_role'] == 'admin') {?>
                        <a href="index.php?page_layout=editnewpro&id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">✏️</a>
                        <a href="deleteproduct.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️</>
                    <?php }?>
                </td>  
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script>
