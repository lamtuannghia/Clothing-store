<?php
    include '../config/connect.php';

    $id = $_GET['id'];

    $detail = mysqli_query($conn,"SELECT * FROM product WHERE id = '$id'");
    $row = $detail->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $size         = $_POST['size'] ?? '';  
        $color        = $_POST['color'] ?? '';
        $quantity     = $_POST['quantity'] ?? [];

        $all_sizes = ["S", "M", "L", "XL", "XXL"]; // Danh sách size mặc định

        $product_id = $row['id'];

        $target_dir = "../assets/uploads/"; 
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $file_name = $target_dir . $image_name;

        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        // Kiểm tra định dạng file ảnh
        if (!in_array($imageFileType, $allowed_types)) {
            die("Chỉ chấp nhận các file JPG, JPEG, PNG, GIF.");
        } else {
            move_uploaded_file($_FILES["image"]["tmp_name"], $file_name);
        }

        // Chèn dữ liệu vào bảng product_inventory
        $sql = "INSERT INTO product_inventory (product_id, color, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $product_id, $color, $image_name);

        if (!$stmt->execute()) {
            die("Lỗi khi chèn sản phẩm: " . $stmt->error);
        }
        $inven_id = $conn->insert_id; // Lấy ID vừa chèn

        $stmt->close();

        // Chuẩn bị câu lệnh INSERT vào bảng size
        $stmt = $conn->prepare("INSERT INTO size (inven_id, size, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $inven_id, $size_value, $quantity_value);

        // Lặp qua tất cả size để chèn dữ liệu
        foreach ($all_sizes as $size_value) {
            // Nếu không nhập số lượng hoặc nhập 0 thì mặc định quantity = 0
            $quantity_value = isset($quantity[$size_value]) && (int)$quantity[$size_value] > 0 ? (int)$quantity[$size_value] : 0;
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        header("Location: index.php?page_layout=detailproduct&id=$product_id");
        exit();
    }
?>
<div class="content">
    <h2>Detail product <?php echo $row['name'] ?></h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <!-- Phần chọn Màu sắc -->
            <div class="mb-3">
                <label class="form-label">Available Colors</label>
                <div class="form-check">
                <ul class="size-option">
                    <li><input type="radio" id="color-black" name="color" value="Đen"> Đen</li>
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

            <div class="mb-3">
                <label class="form-label">Available Sizes & Quantity</label>
                <div class="form-check">
                <ul class="size-list">
                    <li>
                        S <input type="number" class="form-control" name="quantity[S]" placeholder="Enter quantity" min="0">
                    </li>
                    <li>
                        M <input type="number" class="form-control" name="quantity[M]" placeholder="Enter quantity" min="0">
                    </li>
                    <li>
                        L <input type="number" class="form-control" name="quantity[L]" placeholder="Enter quantity" min="0">
                    </li>
                    <li>
                        XL <input type="number" class="form-control" name="quantity[XL]" placeholder="Enter quantity" min="0">
                    </li>
                    <li>
                        XXL <input type="number" class="form-control" name="quantity[XXL]" placeholder="Enter quantity" min="0">
                    </li>
                </ul>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="index.php?page_layout=products" class="btn btn-secondary">Cancel</a>
    </form>
</div>