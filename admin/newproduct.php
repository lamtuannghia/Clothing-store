<?php
include "../config/connect.php";

$cate = mysqli_query($conn,"SELECT * FROM cate");

// Xử lý form khi submit (ở ví dụ này chỉ demo xử lý đơn giản)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name         = $_POST['name'] ?? '';
    $category_id  = $_POST['category_id'] ?? '';
    $price        = $_POST['price'] ?? '';
    $status       = $_POST['status'] ?? '';

    $sql = "INSERT INTO product (name,  price, status, category_id) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $name, $price, $status, $category_id);
    $stmt->execute();
    $product_id = $stmt->insert_id; // Lấy ID sản phẩm vừa thêm
    $stmt->close();

    // 2️⃣ Thêm biến thể (color, image) vào bảng `product_inventory`
    foreach ($_POST['variants'] as $index => $variant) {
        if (!isset($variant['color'])) {
            die("Lỗi: Thiếu dữ liệu color ở biến thể thứ " . ($index + 1));
        }
        $color = $variant['color'];
        
        // Xử lý upload ảnh
        $imageName = "";
        if (!empty($_FILES["variants"]["name"][$index]["image"])) {
            $imageName = time() . "_" . basename($_FILES["variants"]["name"][$index]["image"]);
            move_uploaded_file($_FILES["variants"]["tmp_name"][$index]["image"], "../assets/uploads/" . $imageName);
        }

        $sql = "INSERT INTO product_inventory (product_id, color, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $product_id, $color, $imageName);
        $stmt->execute();
        $inventory_id = $stmt->insert_id; // Lấy ID biến thể vừa thêm
        $stmt->close();

        // 3️⃣ Thêm size & số lượng vào bảng `size`
        if (!empty($variant['sizes'])) {
            foreach ($variant['sizes'] as $size) {
                $size_name = $size['name'];
                $quantity = (int) $size['quantity'];

                $sql = "INSERT INTO size (inven_id, size, quantity) 
                        VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isi", $inventory_id, $size_name, $quantity);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // if($query){
        header("Location: index.php?page_layout=products");
    //     exit();
    // }else{
    //     echo 'Looxi';
    // }
}
?>

<!-- Nội dung chính -->
<div class="content">
    <h2>Add New Product</h2>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success; ?>
        </div>
    <?php endif; ?>

    <form action="newproduct.php" id="productForm" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
        </div>

        <div class="mb-3">
            <label for="cate" class="form-label">Category</label>
            <select class="form-select" id="cate" name="cate_id" required>
                <option value="" disabled selected>---Select category---</option>
                    <?php foreach ($cate as $key => $value){?>
                        <option value="<?php echo $value['id'] ?>">
                            <?php echo $value['name']; ?>
                        </option> 
                    <?php }?>
            </select>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Product Category</label>
            <select class="form-select" id="category" name="category_id" required>
                <option value="" disabled selected>---Select category---</option>  
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (VND)</label>
            <input type="number" class="form-control" id="price" name="price" step="10000" placeholder="Enter price" min="0" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Product Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="">Select status</option>
                <option value="In stock">IN STOCK</option>
                <option value="Pre order">PRE ORDER</option>
                <option value="Sold out">SOLD OUT</option>
                <!-- Thêm các option khác nếu cần -->
            </select>
        </div>

        <h3>Variant:</h3>
        <div class="mb-3" id="variantContainer">
            <!-- Các biến thể màu sẽ thêm vào đây -->
            <button type="button" class="btn btn-secondary" onclick="addVariant()">Add color</button>
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="index.php?page_layout=products" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Link JS từ folder assets -->
<!-- <script src="assets/bootstrap.bundle.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script >
    $(document).ready(function() {
        $("#cate").change(function() {
            var cate_id = $(this).val();

            $.ajax({
                url: "get_category.php", // File xử lý dữ liệu
                type: "POST",
                data: { cate_id: cate_id },
                success: function(response) {
                    $("#category").html(response);
                }
            });
        });
    });
</script>
<script>
    // document.getElementById('cate').addEventListener('change', function() {
    //     let category = this.value;
    //     console.log("Category selected:", category);
    //     let sizeInputs = document.querySelectorAll('.size-container');
    //     sizeInputs.forEach(div => {
    //         div.style.display = (category == 3) ? 'none' : 'block'; // Ẩn size nếu là phụ kiện
    //     });
    // });

    document.getElementById('productForm').addEventListener('submit', function(event) {
        let variants = document.querySelectorAll('.variant'); // Chọn các hàng variant
        if (variants.length < 1) {
            alert("Bạn phải thêm ít nhất một màu của sản phẩm!");
            event.preventDefault();
            return;
        }

        let isValid = true;
        variants.forEach(variant => {
            let sizes = variant.querySelectorAll('.size-container input[name*="quantity"]');
            let hasSize = Array.from(sizes).some(input => input.value > 0); // Kiểm tra có ít nhất 1 size có số lượng > 0

            if (!hasSize) {
                alert("Mỗi biến thể phải có ít nhất một size có số lượng lớn hơn 0.");
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault(); // Ngăn form gửi đi nếu không hợp lệ
        }
    });

    
    function addVariant() {
        let container = document.getElementById('variantContainer');
        let index = container.children.length;
        
        let variantHTML = `
            <div class="variant">
                <div class="mb-3">
                    <label class="form-label">${index}Color</label>
                    <input type="text" class="form-control" name="variants[${index}][color]" placeholder="Enter color" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input class="form-control" type="file" name="variants[${index}][image]" accept="image/*">
                </div>
                <div id="sizeContainer${index}" class="size-container">
                <-- Danh sách size -->
                </div>

                <button type="button" class="btn btn-danger remove-variant">Delete</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', variantHTML);
        addSize(index);
    }

    // 🛠 Hàm cập nhật index sau khi xóa biến thể
    function updateVariantIndexes() {
        let variants = document.querySelectorAll('.variant');
        variants.forEach((variant, newIndex) => {
            variant.setAttribute('data-index', newIndex);
            variant.querySelector('input[name*="[color]"]').name = `variants[${newIndex}][color]`;
            variant.querySelector('input[type="file"]').name = `variants[${newIndex}][images]`;
            
            let sizeInputs = variant.querySelectorAll('input[name*="[sizes]"]');
            sizeInputs.forEach(input => {
                let oldName = input.name;
                let newName = oldName.replace(/variants\[\d+\]/, `variants[${newIndex}]`);
                input.name = newName;
            });

            let sizeContainer = variant.querySelector('.size-container');
            sizeContainer.id = `sizeContainer${newIndex}`;

            let sizeList = sizeContainer.querySelector('.size');
            if (sizeList) {
                sizeList.id = `sizeList${newIndex}`;
            }

            let addSizeButton = sizeContainer.querySelector('button[onclick^="addCustomSize"]');
            if (addSizeButton) {
                addSizeButton.setAttribute("onclick", `addCustomSize(${newIndex})`);
            }
            
            let sizeItems = sizeList.querySelectorAll('.d-flex');
            sizeItems.forEach((sizeItem, sizeIndex) => {
                let sizeNameInput = sizeItem.querySelector('input[type="text"]');
                let quantityInput = sizeItem.querySelector('input[type="number"]');

                sizeNameInput.setAttribute('name', `variants[${newIndex}][sizes][${sizeIndex}][name]`);
                quantityInput.setAttribute('name', `variants[${newIndex}][sizes][${sizeIndex}][quantity]`);
            });
        });
    }

    // 🛠 Xóa biến thể + cập nhật lại index
    document.getElementById('variantContainer').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-variant')) {
            event.target.parentElement.remove();
            updateVariantIndexes(); // Cập nhật lại index sau khi xóa
        }
    });

    function addSize(variantIndex) {
        let container = document.getElementById(`sizeContainer${variantIndex}`);
        let sizeHTML = `
            <div class="mb-3">
                <label class="form-label">Available Sizes & Quantity</label>
                <div id="sizeList${variantIndex}" class="size">
                <!-- Danh sách size được thêm ở đây -->
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="addCustomSize(${variantIndex})">Add Size</button>
            </div>
        `;
        container.innerHTML = sizeHTML;
    }

    function addCustomSize(variantIndex) {
        let sizeList = document.getElementById(`sizeList${variantIndex}`);

        // Lấy danh sách tất cả các input size name trong cùng một variant
        let existingSizes = sizeList.querySelectorAll('input[name^="variants[' + variantIndex + '][sizes]"]');
        
        // Tìm index tiếp theo dựa trên số lượng size hiện có
        let sizeIndex = existingSizes.length; // Chia 2 vì mỗi size có 2 input (name + quantity)

        let sizeItem = document.createElement('div');
        sizeItem.classList.add('d-flex', 'align-items-center', 'mb-2');

        sizeItem.innerHTML = `
            <input type="text" class="form-control me-2" name="variants[${variantIndex}][sizes][${sizeIndex}][name]" placeholder="Enter size" required>
            <input type="number" class="form-control me-2" name="variants[${variantIndex}][sizes][${sizeIndex}][quantity]" placeholder="Enter quantity" min="0" required>
            <button type="button" class="btn btn-danger" onclick="removeSize(this, ${variantIndex})">X</button>
        `;

        sizeList.appendChild(sizeItem);
    }

    function removeSize(button, variantIndex) {
        let sizeList = document.getElementById(`sizeList${variantIndex}`);
        
        // Xóa phần tử cha của nút X (sizeItem)
        button.parentNode.remove();
        
        // Cập nhật lại index của các input còn lại để tránh trùng lặp
        let remainingSizes = sizeList.querySelectorAll('.d-flex');
        remainingSizes.forEach((sizeItem, newIndex) => {
            let sizeNameInput = sizeItem.querySelector('input[type="text"]');
            let quantityInput = sizeItem.querySelector('input[type="number"]');

            sizeNameInput.setAttribute('name', `variants[${variantIndex}][sizes][${newIndex}][name]`);
            quantityInput.setAttribute('name', `variants[${variantIndex}][sizes][${newIndex}][quantity]`);
        });
    }

</script>

