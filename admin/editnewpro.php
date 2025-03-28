<?php
include "../config/connect.php";

$id = $_GET['id'];

$sql = "SELECT p.name, p.price, p.status, c.name as CateName, p.category_id
        FROM product p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = $result->fetch_assoc();

$categories = mysqli_query($conn,"SELECT * FROM categories"); 
// Xử lý form khi submit (ở ví dụ này chỉ demo xử lý đơn giản)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name         = $_POST['name'] ?? '';
    $category_id  = $_POST['cate_id'] ?? '';
    $price        = $_POST['price'] ?? '';
    $status       = $_POST['status'] ?? '';

    $sql = "UPDATE product
            SET name = '$name', price = '$price', status = '$status', category_id = '$category_id'
            WHERE id = '$id'";
    
    $query = mysqli_query($conn, $sql);

    if($query){
        header("Location: index.php?page_layout=products");
        exit();
    }else{
        echo 'Looxi';
    }
}
?>

    <!-- Nội dung chính -->
    <div class="content">
        <h2>Edit Product</h2>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" value="<?= $row['name']?>" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Product Category</label>
                <select class="form-select" id="category" name="cate_id" required>
                    <option value="<?= $row['category_id']?>"><?= $row['CateName']?></option>
                        <?php foreach ($categories as $key => $value){?>
                            <option value="<?php echo $value['id'] ?>">
                                <?php echo $value['name']; ?>
                            </option> 
                        <?php }?>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (VND)</label>
                <input type="number" class="form-control" id="price" name="price" step="10000"  value="<?= $row['price']?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Product Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="<?= $row['status']?>"><?php echo $row['status'] ?></option>
                    <option value="In stock">IN STOCK</option>
                    <option value="Pre order">PRE ORDER</option>
                    <option value="Sold out">SOLD OUT</option>
                    <!-- Thêm các option khác nếu cần -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Edit Product</button>
            <a href="index.php?page_layout=products" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Link JS từ folder assets -->
    <script src="assets/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
