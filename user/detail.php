<?php
// Kết nối database
include 'config/connect.php';

$product_id = $_GET['id'] ?? 1; // Lấy ID sản phẩm từ URL

$stmt = $conn->prepare("
    SELECT p.name AS product_name, p.price, pi.image, pi.color
    FROM product p
    JOIN product_inventory pi ON p.id = pi.product_id
    WHERE p.id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$product = [];
$sizes = ['S', 'M', 'L', 'XL', 'XXL'];
$colors = [];
$images = [];

while ($row = $result->fetch_assoc()) {
    $images[] = $row['image'];

    if (empty($product)) {
        $product = [
            'name' => $row['product_name'],
            'price' => number_format($row['price'], 0, ',', '.'),
        ];
    }
    if (!in_array($row['color'], $colors)) {
        $colors[] = $row['color'];
    }
}

$stmt_pro = $conn->prepare("SELECT p.id, p.name, p.price, pi.image, p.quantity 
            FROM product p
            JOIN product_inventory pi ON p.id = pi.product_id
            GROUP BY p.id
            ORDER BY RAND() LIMIT 5");
    
$stmt_pro->execute();
$result_pro = $stmt_pro->get_result();


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

    <div class="content">
        <div class="page-wrapper">
            <!-- Sidebar Danh Mục -->
            <div class="sidebar">
                <h4>Danh mục</h4>
                <ul>
                <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="index.php?page_layout=product&cate_id=<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                </ul>
            </div>

            <!-- Hình ảnh sản phẩm -->
            <div class="product-display">
                <div class="detail-image-container">
                    <?php foreach ($images as $index => $image): ?>
                        <img src="assets/uploads/<?php echo $image; ?>" class="detail-image" onclick="changeImage('<?php echo $image; ?>')">
                    <?php endforeach; ?>
                </div>

                <div class="main-image">
                    <?php foreach ($images as $index => $image): ?>
                        <img id="image-<?php echo $index; ?>" src="assets/uploads/<?php echo $image; ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <form action="user/addtocart.php" method="POST" class="product-details">
                <h2 name="name"><?= $product['name'] ?></h2>
                <p name="price"> <?= $product['price'] ?> ₫</p>
                <p>
                    <?php foreach ($images as $color => $image): ?>
                        <a class="color-option" data-color="<?= $colors[$color] ?>" data-image="assets/uploads/<?= $image ?>">
                            <span class="color-label" style="position: absolute; background: #fff; color:black; padding: 2px 5px; border-radius: 5px; font-size: 18px; display: none; transform: translateY(-120%); border: 1px solid #ccc"> <?php echo  $colors[$color] ?> </span>
                            <img id="product-image" src="assets/uploads/<?= $image ?>" alt="<?= $color ?>" style="width: 40px; height: 40px; border-radius: 50%;">
                        </a>
                    <?php endforeach; ?>
                </p>
                <div class="size-buttons">
                    <?php 
                    foreach ($sizes as $size): ?>
                        <a class="size-btn" disabled data-size="<?= $size; ?>"><?= $size; ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="quantity-selector">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                    <input type="text" name="quantity" class="quantity-input" id="quantity" value="1" readonly>
                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                </div>

                <input type="hidden" id="product-id" name="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="selected_color" id="selected_color">
                <input type="hidden" name="selected_size" id="selected_size">
                                
                <button type="submit" name="add_to_cart"  class="buy-btn">Chọn mua</button>
                <?php
                    if (isset($_SESSION['success'])) {
                        echo "<p style='color: grey; 
                                        font-weight: lighter;
                                        border: 1px solid #383d41; border-radius: 5px; text-align: center; width: 300px; margin: 50px auto;
                                        '>" . $_SESSION['success'] . "</p>";
                        unset($_SESSION['success']); // Xóa session để tránh hiển thị lại sau khi reload
                    }
                ?>
            </form>
        </div>
        <div class="suggest-product">
            <h2 class="title">SẢN PHẨM MỚI</h2>
            <div class="product-container">
                <?php while ($row = $result_pro->fetch_assoc()): ?>
                    <div class="product">
                        <a href="index.php?page_layout=detail&id= <?= $row['id'] ?> ">
                            <div class="product-image">
                                <img src="assets/uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                                <?php if ($row['quantity'] == '0'): ?>
                                    <span class="sold-out">HẾT HÀNG</span>
                                <?php endif; ?>
                            </div>
                            <!-- <div class="product-actions">
                                <button class="quick-view" data-id= <?= $row['id'] ?>>
                                    <span>Xem nhanh <i class="fa fa-eye"></i></span>
                                </button>
                                    <span>|</span>
                                <button>
                                    <span>Mua ngay <i class="fa fa-shopping-cart"></i></span>
                                </button>
                            </div> -->
                            <p class="product-name"><?= strtoupper($row['name']) ?></p>
                            <p class="product-price"><?= number_format($row['price'], 0, ',', '.') ?> đ</p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php include "footer.php" ?>
<script>
    document.querySelectorAll('.color-option').forEach(colorOption => {
        let colorLabel = colorOption.querySelector('.color-label');
        
        colorOption.addEventListener('mouseover', function() {
            colorLabel.style.display = 'block';
        });
        
        colorOption.addEventListener('mouseout', function() {
            colorLabel.style.display = 'none';
        });
        
        
            document.querySelector('.buy-btn').removeAttribute('disabled');
        });


    let maxQuantity = 1;
    let quantityInput = document.getElementById("quantity");

    document.addEventListener("DOMContentLoaded", function () {
        const colorOptions = document.querySelectorAll(".color-option");
        const sizeButtons = document.querySelectorAll(".size-btn");
        const buyButton = document.querySelector(".buy-btn");
        const sidebar = document.querySelector(".sidebar");
        const productDetails = document.querySelector(".product-details");
        const productImage = document.querySelector(".product-display");
        
        let selectedColor = "";
        let selectedSize = "";
        let productId = document.getElementById("product-id").value;

        // Xử lý khi chọn màu
        colorOptions.forEach(option => {
            option.addEventListener("click", function (event) {
                event.preventDefault();
                colorOptions.forEach(opt => opt.style.border = "none");
                this.style.border = "2px solid black";

                selectedColor = this.getAttribute("data-color");
                document.getElementById('selected_color').value = selectedColor;

                // Gọi API để lấy size theo màu đã chọn
                fetch(`user/product_api.php?action=get_sizes&product_id=${productId}&color=${selectedColor}`)
                    .then(response => response.json())
                    .then(data => {
                        sizeButtons.forEach(a => {
                            let sizeValue = a.getAttribute("data-size");
                            let sizeInfo = data.find(s => s.name.toUpperCase() === sizeValue.toUpperCase());

                            if (sizeInfo && sizeInfo.quantity > 0) {
                                a.style.background = "white";
                                a.style.color = "black";
                                a.style.cursor = "pointer";
                                a.style.pointerEvents = "auto";
                            } else {
                                a.style.color = "gray";
                                a.style.cursor = "not-allowed";
                                a.style.pointerEvents = "none";
                            }
                        });
                    });
            });
        });

        // Xử lý khi chọn size
        sizeButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                if (!selectedColor) return;
                sizeButtons.forEach(a => {
                    a.style.backgroundColor = "white";
                    a.style.color = "black";
                });

                this.style.backgroundColor = "black";
                this.style.color = "white";

                selectedSize = this.getAttribute("data-size");
                document.getElementById('selected_size').value = selectedSize;

                // Gọi API để lấy số lượng tồn kho
                fetch(`user/product_api.php?action=get_stock&product_id=${productId}&color=${selectedColor}&size=${selectedSize}`)
                    .then(response => response.json())
                    .then(data => {
                        maxQuantity = data.stock;
                        quantityInput.value = 1;
                    });
            });
        });

    // Kiểm tra trước khi thêm vào giỏ hàng
        buyButton.addEventListener("click", function (event) {
            if (!selectedColor || !selectedSize) {
                event.preventDefault();
                alert("Vui lòng chọn màu sắc và kích thước trước khi thêm vào giỏ hàng!");
            }
        });
    });
    function changeQuantity(change) {
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + change;
            
            if (newQuantity >= 1 && newQuantity <= maxQuantity) {
                quantityInput.value = newQuantity;
            }
        }

    function changeImage(imageSrc) {
        document.getElementById("mainImage").src = "assets/uploads/" + imageSrc;
    }
    const wrap = document.getElementById('myWrapper');

    window.addEventListener('scroll', function() {
    if (window.scrollY === 0) {
        // Khi cuộn lên đầu trang, hiện thẻ div
        wrap.style.display = "relative";
    } else {
        // Khi cuộn xuống, ẩn thẻ div
        wrap.style.display = "fixed";
    }
    });

    function scrollToImage(imageIndex) {
    // Lấy ảnh tương ứng trong main-image
    let targetImage = document.getElementById("image-" + imageIndex);
    
    if (targetImage) {
        targetImage.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }
    }

    // Thêm sự kiện cho ảnh nhỏ
    document.querySelectorAll('.detail-image').forEach((img, index) => {
        img.addEventListener('click', () => scrollToImage(index));
    });
</script>