<?php
    include 'config/connect.php';

    $cate_id = isset($_GET['cate_id']) ? intval($_GET['cate_id']) : null;
    $subcate = isset($_GET['subcate']) ? $_GET['subcate'] : null;

    if (isset($_GET['cate_id'])) {
        // Nếu chọn danh mục cha, hiển thị tất cả sản phẩm trong danh mục đó
        $cate_id = intval($_GET['cate_id']);
        $stmt = $conn->prepare("
            SELECT p.id, p.name, p.price, pi.image, p.quantity
            FROM product p
            JOIN product_inventory pi ON p.id = pi.product_id
            LEFT JOIN categories ca ON p.category_id = ca.id
            LEFT JOIN cate c ON c.id = ca.cate_id
            WHERE ca.cate_id = ?
            GROUP BY p.id
        ");
        $cate = $conn->prepare("
            SELECT ca.name
            FROM categories ca
            JOIN cate c ON c.id = ca.cate_id
            WHERE ca.cate_id = ?;
            ");
        $stmt->bind_param("i", $cate_id);
        $cate->bind_param("i", $cate_id);
    } elseif (isset($_GET['subcate'])) {
        // Nếu chọn danh mục con, hiển thị sản phẩm của danh mục con đó
        $subcate = $_GET['subcate'];
        $stmt = $conn->prepare("
            SELECT p.id, p.name, p.price, pi.image, p.quantity
            FROM product p
            JOIN product_inventory pi ON p.id = pi.product_id
            LEFT JOIN categories ca ON p.category_id = ca.id
            LEFT JOIN cate c ON c.id = ca.cate_id
            WHERE ca.name = ?
            GROUP BY p.id
        ");
        $cate = $conn->prepare("
            SELECT name
            FROM cate
            ");
        $stmt->bind_param("s", $subcate);
    } else {
        // Nếu không chọn gì, hiển thị tất cả sản phẩm
        $stmt = $conn->prepare("SELECT p.id, p.name, p.price, pi.image, p.quantity 
            FROM product p
            JOIN product_inventory pi ON p.id = pi.product_id
            GROUP BY p.id");
        $cate = $conn->prepare("
        SELECT name
        FROM cate
        ");
    }
            
    $stmt->execute();
    $result = $stmt->get_result();

    $cate->execute();
    $result_cate = $cate->get_result();
    
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
<body>
    <div class="main-product">
        <aside class="sidebar filter-section">
            <div>
                <!-- <h4>TÌM KIẾM</h4> -->
                <input type="text" id="searchInput" placeholder="TÌM KIẾM">
                <span class="icon-search" title="Tìm kiếm">
                    <a aria-label="search" id="searchButton">
                        <span class="search-menu" aria-hidden="true">
                            <img src="assets/image/icon-header-search.png" class="image-search">
                        </span>
                    </a>
                </span>
            </div>

            <h4>GIÁ</h4>
            <div class="sort-options">
                <select id="sort">
                    <option value="latest">Mới nhất</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                </select>
            </div>
            <!-- <span id="priceValue">0đ <input type="range" id="priceRange" min="0" max="3000000" step="10000" value="3000000"> 3,000,000đ</span> -->
            
            <h4>SẢN PHẨM</h4>
            <ul>
                <?php
                    while($row = $result_cate->fetch_assoc()){
                        echo '<li><input type="checkbox" class="category" value="'.$row['name'].'">'.$row['name'].'</li>';
                    }
                ?>
            </ul>
            <h4>MÀU</h4>
            <ul>
                <li><input type="checkbox" id="color-black" class="color" value="Đen"> Đen</li>
                <li><input type="checkbox" id="color-white" class="color" value="Trắng"> Trắng</li>
                <li><input type="checkbox" id="color-beige" class="color" value="Be"> Be</li>
                <li><input type="checkbox" id="color-red" class="color" value="Đỏ"> Đỏ</li>
                <li><input type="checkbox" id="color-blue" class="color" value="Xanh"> Xanh</li>
                <li><input type="checkbox" id="color-lightblue" class="color" value="Xanh nhạt"> Xanh nhạt</li>
                <li><input type="checkbox" id="color-darkblue" class="color" value="Xanh than"> Xanh Than</li>
                <li><input type="checkbox" id="color-orange" class="color" value="Xanh rêu"> Xanh rêu</li>
                <li><input type="checkbox" id="color-lightgrey" class="color" value="Xám nhạt"> Xám nhạt</li>
                <li><input type="checkbox" id="color-grey" class="color" value="Xám"> Xám</li>
                <li><input type="checkbox" id="color-brown" class="color" value="Nâu"> Nâu</li> 
            </ul> 

            <h4>SIZE CHỮ</h4>
            <ul>
                <li><input type="checkbox" class="size" value="S"> S</li>
                <li><input type="checkbox" class="size" value="M"> M</li>
                <li><input type="checkbox" class="size" value="L"> L</li>
                <li><input type="checkbox" class="size" value="XL"> XL</li>
                <li><input type="checkbox" class="size" value="XXL"> XXL</li>
            </ul>
        </aside>
        
        <main class="product-list">
            <div class="product-list" id="product-list">
                <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="product-item">';
                            if ($row['quantity'] == 0) {
                                echo '<span class="out-of-stock">HẾT HÀNG</span>';
                            }
                            
                            echo '<a href="index.php?page_layout=detail&id=' . $row['id'] . '">';
                            echo '<img src="assets/uploads/' . $row["image"] . '" alt="' . htmlspecialchars($row["name"]) . '">';
                            echo '</a>';

                            echo '<div class="product-actions">';
                            echo '<button class="quick-view" data-id=' . $row['id'] . '>';
                            echo    '<span>Xem nhanh <i class="fa fa-eye"></i></span>';
                            echo '</button>';
                            echo    '<span>|</span>';
                            echo '<button class="buy" data-id=' . $row['id'] . '>';
                            echo    '<span>Mua ngay <i class="fa fa-shopping-cart"></i></span>';
                            echo '</button>';
                            echo '</div>';

                            echo '<a class="detail-product" href="index.php?page_layout=detail&id=' . $row['id'] . '">';
                            echo '<h4>' . htmlspecialchars($row["name"]) . '</h4>';
                            echo '<p>' . number_format($row["price"]) . 'đ</p>';
                            echo '</a>';

                            echo '</div>';
                        }
                    }
                ?>
            </div>
            <input type="hidden" name="cate_id" id="cate_id" value="<?= $cate_id ?>">
            <!-- Overlay làm mờ nền -->
            <div id="overlay" onclick="hideProductInfo()"></div>

            <!-- Modal hiển thị thông tin sản phẩm -->
            <div>
                <div id="productInfo">
                    <div id="quickViewContent"></div>
                </div>
            </div>
        </main>
    </div>
    <?php include "footer.php" ?>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".quick-view").forEach(button => {
            button.addEventListener("click", function () {
                let productId = this.dataset.id; // Lấy ID từ data-id
                fetch("user/quick-view.php?id=" + productId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("quickViewContent").innerHTML = data;
                        document.getElementById("overlay").style.display = "block";
                        document.getElementById("productInfo").style.display = "block";
                        document.body.style.overflow = "hidden";
                    })
                    .catch(error => console.error("Lỗi Fetch:", error));
            });
        });
    });

    function hideProductInfo() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("productInfo").style.display = "none";
        document.body.style.overflow = "";
    }
    function changeImage(imageSrc) {
        document.getElementById("mainImage").src = "assets/uploads/" + imageSrc;
    }

    document.addEventListener("DOMContentLoaded", function () {
        let searchInput = document.getElementById("searchInput");
        let searchButton = document.getElementById("searchButton");
        let sortSelect = document.getElementById("sort");
        // let cateid = document.getElementById("cate_id");
        let id = document.getElementById("cate_id").value;


        function getSelectedValues(className) {
            return Array.from(document.querySelectorAll(`.${className}:checked`))
                .map(el => el.value);
        }

        function searchProducts() {
            let query = searchInput.value.trim();
            let sort = sortSelect.value;
            let cate = getSelectedValues("category");
            let colors = getSelectedValues("color");
            let sizes = getSelectedValues("size");

            let params = new URLSearchParams();
            if (id) params.append("id", id);
            if (query) params.append("query", query);
            if (sort) params.append("sort", sort);
            if (cate.length > 0) params.append("cate", cate.join(","));
            if (colors.length > 0) params.append("colors", colors.join(","));
            if (sizes.length > 0) params.append("sizes", sizes.join(","));
 
            // // Kiểm tra nếu không có bộ lọc nào -> Làm mới trang về danh sách ban đầu
            // if (!query && !sort && cate.length === 0 && colors.length === 0 && sizes.length === 0) {
            //         // window.location.reload();  // Refresh trang để load danh sách gốc
            //         window.location.replace("index.php?page_layout=product&cate_id=" + $id);
            //         return;
            //     }

            fetch("user/search.php?"+ params.toString())
                .then(response => response.text())
                .then(data => {
                    document.querySelector(".product-list").innerHTML = data;
                })
                .catch(error => console.error("Lỗi tìm kiếm sản phẩm:", error));
        }



        searchInput.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                searchProducts();
            }
        });

        searchButton.addEventListener("click", function (event) {
            event.preventDefault();
            searchProducts();
        });

        sortSelect.addEventListener("change", searchProducts);
        document.querySelectorAll(" .category, .color, .size").forEach(input => {
            input.addEventListener("change", searchProducts);
        });
    });

    </script>
</body>