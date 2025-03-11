<?php
include "../config/connect.php";
$id = $_GET['id'];

// Lấy danh sách sản phẩm từ database
$sql = "SELECT p.id ,p.color, p.image, s.size, s.quantity, p.product_id
        FROM product_inventory p
        JOIN size s ON p.id = s.inven_id
        WHERE p.id = '$id'"; // Sắp xếp theo ID mới nhất

$result = mysqli_query($conn, $sql);

$row = $result->fetch_assoc();

$pro_id = $row['product_id'];

$all_sizes = ["S", "M", "L", "XL", "XXL"];

// Truy vấn lấy size và quantity từ database
$query_size = "SELECT size, quantity FROM size WHERE inven_id = $id";
$stmt = $conn->prepare($query_size);
$stmt->execute();
$result_size = $stmt->get_result();

// Lưu size và quantity từ database vào mảng
$sizes = [];
while ($row_size = $result_size->fetch_assoc()) {
    $sizes[$row_size['size']] = $row_size['quantity'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

        // echo "<pre class='content'>";
        // print_r($_POST);
        // print_r($id);
        // echo "</pre>";
        // exit;

    $quantity     = $_POST['quantity'] ?? [];
    $size         = $_POST['size'] ?? [];  
    $color        = $_POST['color'] ?? '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }
    

    $stmt = $conn->prepare("UPDATE product_inventory 
                SET color = COALESCE(NULLIF(?, ''), color), 
                image = CASE WHEN ? IS NOT NULL AND ? != '' THEN ? ELSE image END
                WHERE id = ?");
    $stmt->bind_param("ssssi", $color, $image, $image, $image, $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE size SET quantity = ? WHERE inven_id = ? AND size = ?");
    $stmt->bind_param("iis", $quantity_value, $id, $size);
        
    foreach ($all_sizes as $size) {
        $quantity_value = isset($quantity[$size]) && (int)$quantity[$size] > 0 ? (int)$quantity[$size] : 0;
        $stmt->execute();
    }

    // Chuyển hướng về trang chi tiết sản phẩm sau khi cập nhật thành công
    header("Location: index.php?page_layout=detailproduct&id=$pro_id");
    exit();
}
?>
    <style>
        img { width: 150px; height: auto; margin: 10px; display: block; }
        /* form { display: inline-block; } */
    </style>
    <!-- Nội dung chính -->
    <div class="content">
        <h2>Edit Product</h2>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <!-- Phần chọn Size -->
            <div class="mb-3">
            <label class="form-label">Available Sizes</label>
            <div class="form-check">
                <ul class="size-list">
                <?php foreach ($all_sizes as $size) : 
                    $quantity = isset($sizes[$size]) ? (int)$sizes[$size] : 0; // Lấy số lượng hoặc mặc định 0
                ?>
                    <li>
                        <label><?php echo htmlspecialchars($size); ?></label>
                        <input type="number" class="form-control" name="quantity[<?php echo htmlspecialchars($size); ?>]" value="<?php echo htmlspecialchars($quantity); ?>" placeholder="Enter quantity" min="0">
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            </div>

          <!-- Phần chọn Màu sắc -->
          <div class="mb-3">
              <label class="form-label">Available Colors</label>
              <div class="form-check">
              <ul class="size-option">
                    <li><input type="radio" name="color" value="<?php echo $row['color']; ?>" <?php echo $row['color'] ? "checked" : ""; ?>> <?php echo $row['color']; ?></li>
                    <li><input type="radio" id="color-black" name="color" value="Đen" > Đen</li>
                    <li><input type="radio" id="color-white" name="color" value="Trắng"> Trắng</li>
                    <li><input type="radio" id="color-beige" name="color" value="Be"> Be</li>
                    <li><input type="radio" id="color-red" name="color" value="Đỏ"> Đỏ</li>
                    <li><input type="radio" id="color-blue" name="color" value="Xanh"> Xanh</li>
                    <li><input type="radio" id="color-lightblue" name="color" value="Xanh nhạt"> Xanh nhạt</li>
                    <li><input type="radio" id="color-darkblue" name="color" value="Xanh than"> Xanh Than</li>
                    <li><input type="radio" id="color-orange" name="color" value="Xanh rêu"> Xanh rêu</li>
                    <li><input type="radio" id="color-lightgrey" name="color" value="Xám nhạt"> Xám nhạt</li>
                    <li><input type="radio" id="color-grey" name="color" value="Xám"> Xám</li>
                    <li><input type="radio" id="color-brown" name="color" value="Nâu"> Nâu</li>
                </ul>
              </div>
          </div>

            <!-- <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
            </div> -->

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <?php
                    echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
                    $imageArray = explode(',', $row['image']);

                    // Hiển thị tất cả ảnh
                    foreach ($imageArray as $image) {
                        echo '<img src="../assets/uploads/' . htmlspecialchars($image) . '" alt="Ảnh" style="width:200px; margin-right:5px;">';
                    }
                    echo '</div>';
                ?>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">New Product Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" >
            </div>

            <button type="submit" class="btn btn-primary">Edit Product</button>
            <a href="index.php?page_layout=detailproduct" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Link JS từ folder assets -->
    <script src="assets/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
