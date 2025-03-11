<?php
include '../config/connect.php'; // Kết nối database

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Lấy thông tin sản phẩm
    $query = "SELECT p.name AS product_name, p.price,  pi.image, pi.color, s.size, s.quantity
              FROM product p
              JOIN product_inventory pi ON p.id = pi.product_id
              LEFT JOIN size s ON pi.id = s.inven_id
              WHERE s.quantity > 0 AND p.id = ?
              GROUP BY pi.id";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sizes = ['S', 'M', 'L', 'XL', 'XXL'];

    $product = [];
    while ($row = $result->fetch_assoc()) {
        $product['name'] = $row['product_name'];
        $product['price'] = $row['price'];
        $product['images'][] = $row['image'];
        $product['colors'][] = $row['color'];
        // $product['sizes'][] = $row['size'];
    }
}
?>
<head>
    <!-- <?php
    if (isset($_GET['page_layout'])) {
        $page_css = "assets/css/" . $_GET['page_layout'] . ".css";
        if (file_exists($page_css)) {
            echo '<link rel="stylesheet" href="' . $page_css . '">';
        }
    }
    ?> -->
    <link rel="stylesheet" href="assets/css/quick-view.css">
</head>
<div id="quickViewModal" class="modal">
    <div class="modal-content">
        <div class="modal-body">
            <!-- Cột trái: Ảnh sản phẩm -->
            <div class="product-images">
                <img id="mainImage" src="assets/uploads/<?php echo $product['images'][0]; ?>" alt="Ảnh sản phẩm">
                <div class="thumbnail-container">
                    <?php foreach ($product['images'] as $img) : ?>
                        <img class="thumbnail" src="assets/uploads/<?php echo $img; ?>" onclick="changeImage('<?php echo $img; ?>')">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cột phải: Thông tin sản phẩm -->
            <div class="product-details">
            <span onclick="hideProductInfo()" class="close" >✖</span>
                <h2><?php echo $product['name']; ?></h2>
                <p><strong>Giá:</strong> <?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                <p>
                    <?php foreach ($product['colors'] as $index => $color) : ?>
                        <a class="color-option" data-color="<?= $color; ?>">
                        <!-- <strong>Màu sắc:</strong><br> -->
                            <span class="color-label" style="position: absolute; background: #fff; color:black; padding: 2px 5px; border-radius: 5px; font-size: 18px; display: none; transform: translateY(-120%); border: 1px solid #ccc"><?= $color; ?></span>
                            <img id="product-image" src="assets/uploads/<?= $product['images'][$index] ?>" alt="<?= $color?>" style="width: 40px; height: 40px; border-radius: 50%;">
                        </a>
                    <?php endforeach; ?>
                </p>
                <div class="size-buttons">
                    <!-- <strong>Kích thước:</strong> -->
                    <?php foreach ($sizes as $size) : ?>
                        <a class="size-btn" disabled data-size="<?= $size; ?>"><?= $size; ?></a>
                    <?php endforeach; ?>
                </div>

                <input type="hidden" id="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="selected_color" id="selected_color">
                <input type="hidden" name="selected_size" id="selected_size">
                <input type="hidden" name="quantity" class="quantity-input" id="quantity" value="1" readonly>

                <button class="buy-btn">Thêm vào giỏ</button>
                <p><a href="index.php?page_layout=detail&id=<?php echo $product_id; ?>">Xem chi tiết</a></p>
            </div>
        </div>
    </div>
</div>

<script>

    function closeQuickView() {
        document.getElementById("quickViewModal").style.display = "none";
    }

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
</script>   
